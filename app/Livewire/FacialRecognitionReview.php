<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\PhotoTag;
use App\Services\FacialRecognitionService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class FacialRecognitionReview extends Component implements HasForms
{
    use InteractsWithForms;

    public ?int $currentTagIndex = 0;
    public $pendingTags = [];
    public ?int $selectedPersonId = null;
    public bool $createEncoding = true;

    protected FacialRecognitionService $facialRecognitionService;

    public function boot(FacialRecognitionService $facialRecognitionService): void
    {
        $this->facialRecognitionService = $facialRecognitionService;
    }

    public function mount(): void
    {
        $this->loadPendingTags();
    }

    public function loadPendingTags(): void
    {
        $teamId = auth()->user()->currentTeam?->id;
        $tags = $this->facialRecognitionService->getPendingTags($teamId);
        
        $this->pendingTags = $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'photo_url' => $tag->photo->url,
                'person_id' => $tag->person_id,
                'person_name' => $tag->person?->fullname(),
                'confidence' => $tag->confidence,
                'bounding_box' => $tag->bounding_box,
            ];
        })->toArray();

        $this->currentTagIndex = 0;
        $this->selectedPersonId = $this->pendingTags[0]['person_id'] ?? null;
    }

    public function confirmTag(): void
    {
        if (empty($this->pendingTags) || !isset($this->pendingTags[$this->currentTagIndex])) {
            return;
        }

        $tagData = $this->pendingTags[$this->currentTagIndex];
        $tag = PhotoTag::find($tagData['id']);

        if (!$tag) {
            Notification::make()
                ->title('Tag not found')
                ->danger()
                ->send();
            return;
        }

        if ($this->selectedPersonId && $this->selectedPersonId !== $tag->person_id) {
            $this->facialRecognitionService->updateTagPerson(
                $tag,
                $this->selectedPersonId,
                auth()->id()
            );
        } else {
            $this->facialRecognitionService->confirmTag(
                $tag,
                auth()->id(),
                $this->createEncoding
            );
        }

        Notification::make()
            ->title('Tag confirmed')
            ->success()
            ->send();

        $this->nextTag();
    }

    public function rejectTag(): void
    {
        if (empty($this->pendingTags) || !isset($this->pendingTags[$this->currentTagIndex])) {
            return;
        }

        $tagData = $this->pendingTags[$this->currentTagIndex];
        $tag = PhotoTag::find($tagData['id']);

        if (!$tag) {
            Notification::make()
                ->title('Tag not found')
                ->danger()
                ->send();
            return;
        }

        $this->facialRecognitionService->rejectTag($tag);

        Notification::make()
            ->title('Tag rejected')
            ->success()
            ->send();

        $this->nextTag();
    }

    public function skipTag(): void
    {
        $this->nextTag();
    }

    protected function nextTag(): void
    {
        if ($this->currentTagIndex < count($this->pendingTags) - 1) {
            $this->currentTagIndex++;
            $this->selectedPersonId = $this->pendingTags[$this->currentTagIndex]['person_id'] ?? null;
        } else {
            // All tags reviewed, reload
            $this->loadPendingTags();
        }
    }

    public function previousTag(): void
    {
        if ($this->currentTagIndex > 0) {
            $this->currentTagIndex--;
            $this->selectedPersonId = $this->pendingTags[$this->currentTagIndex]['person_id'] ?? null;
        }
    }

    public function getCurrentTag(): ?array
    {
        return $this->pendingTags[$this->currentTagIndex] ?? null;
    }

    public function render()
    {
        $currentTag = $this->getCurrentTag();
        $teamId = auth()->user()->currentTeam?->id;
        
        $peopleOptions = Person::query()
            ->when($teamId, fn($q) => $q->where('team_id', $teamId))
            ->get()
            ->mapWithKeys(fn($person) => [$person->id => $person->fullname()])
            ->toArray();

        return view('livewire.facial-recognition-review', [
            'currentTag' => $currentTag,
            'totalTags' => count($this->pendingTags),
            'peopleOptions' => $peopleOptions,
        ]);
    }
}
