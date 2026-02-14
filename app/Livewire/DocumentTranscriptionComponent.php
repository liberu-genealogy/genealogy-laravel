<?php

namespace App\Livewire;

use App\Models\DocumentTranscription;
use App\Models\User;
use App\Services\HandwritingRecognitionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class DocumentTranscriptionComponent extends Component
{
    use WithFileUploads;

    public $document;
    public ?DocumentTranscription $currentTranscription = null;
    public string $transcriptionText = '';
    public array $transcriptions = [];
    public bool $isUploading = false;
    public bool $isEditing = false;
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    public function mount(): void
    {
        $this->loadTranscriptions();
    }

    public function loadTranscriptions(): void
    {
        $user = Auth::user();
        $teamId = $user->currentTeam?->id ?? $user->latestTeam?->id;

        if ($teamId) {
            $this->transcriptions = DocumentTranscription::where('team_id', $teamId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->toArray();
        }
    }

    public function updatedDocument(): void
    {
        $this->validate([
            'document' => 'required|image|max:10240', // 10MB max
        ]);
    }

    public function uploadDocument(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $this->validate([
            'document' => 'required|image|max:10240',
        ]);

        $this->isUploading = true;

        try {
            $user = Auth::user();
            $teamId = $user->currentTeam?->id ?? $user->latestTeam?->id;

            if (!$teamId) {
                throw new \Exception('No team selected');
            }

            $service = app(HandwritingRecognitionService::class);
            $transcription = $service->processDocument($this->document, $user, $teamId);

            $this->currentTranscription = $transcription;
            $this->transcriptionText = $transcription->getCurrentTranscription() ?? '';

            $this->successMessage = 'Document uploaded and transcription completed!';
            $this->document = null;
            $this->loadTranscriptions();
        } catch (\Exception $e) {
            $this->errorMessage = 'Upload failed: ' . $e->getMessage();
        } finally {
            $this->isUploading = false;
        }
    }

    public function selectTranscription(int $transcriptionId): void
    {
        $this->currentTranscription = DocumentTranscription::find($transcriptionId);
        
        if ($this->currentTranscription) {
            $this->transcriptionText = $this->currentTranscription->getCurrentTranscription() ?? '';
            $this->isEditing = false;
            $this->errorMessage = null;
        }
    }

    public function startEditing(): void
    {
        $this->isEditing = true;
    }

    public function cancelEditing(): void
    {
        if ($this->currentTranscription) {
            $this->transcriptionText = $this->currentTranscription->getCurrentTranscription() ?? '';
        }
        $this->isEditing = false;
    }

    public function saveCorrection(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        if (!$this->currentTranscription) {
            $this->errorMessage = 'No transcription selected';
            return;
        }

        try {
            $user = Auth::user();
            $service = app(HandwritingRecognitionService::class);

            $service->applyCorrection(
                $this->currentTranscription,
                $user,
                $this->transcriptionText
            );

            $this->currentTranscription->refresh();
            $this->isEditing = false;
            $this->successMessage = 'Correction saved successfully!';
            $this->loadTranscriptions();
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to save correction: ' . $e->getMessage();
        }
    }

    public function deleteTranscription(int $transcriptionId): void
    {
        try {
            $transcription = DocumentTranscription::find($transcriptionId);
            
            if ($transcription && $transcription->team_id === (Auth::user()->currentTeam?->id ?? Auth::user()->latestTeam?->id)) {
                $transcription->delete();
                
                if ($this->currentTranscription && $this->currentTranscription->id === $transcriptionId) {
                    $this->currentTranscription = null;
                    $this->transcriptionText = '';
                }
                
                $this->successMessage = 'Transcription deleted successfully';
                $this->loadTranscriptions();
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to delete transcription: ' . $e->getMessage();
        }
    }

    #[On('transcriptionUpdated')]
    public function refreshTranscription(): void
    {
        if ($this->currentTranscription) {
            $this->currentTranscription->refresh();
            if (!$this->isEditing) {
                $this->transcriptionText = $this->currentTranscription->getCurrentTranscription() ?? '';
            }
        }
        $this->loadTranscriptions();
    }

    public function render()
    {
        return view('livewire.document-transcription-component', [
            'stats' => $this->getStats(),
        ]);
    }

    private function getStats(): array
    {
        $user = Auth::user();
        $teamId = $user->currentTeam?->id ?? $user->latestTeam?->id;

        if (!$teamId) {
            return [
                'total' => 0,
                'completed' => 0,
                'pending' => 0,
            ];
        }

        $service = app(HandwritingRecognitionService::class);
        return $service->getTeamStats($teamId);
    }
}
