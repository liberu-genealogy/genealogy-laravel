<?php

namespace App\Filament\App\Resources\DnaResource\Pages;

use App\Filament\App\Resources\DnaResource;
use App\Models\Dna;
use App\Services\DnaImportService;
use Exception;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateDna extends CreateRecord
{
    protected static string $resource = DnaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $files = $data['attachment'] ?? [];

        if (empty($files)) {
            Notification::make()
                ->title('Please upload at least one DNA file to continue.')
                ->danger()
                ->send();

            $this->halt();
        }

        $importService = app(DnaImportService::class);
        $files = is_array($files) ? $files : [$files];
        $firstDna = null;
        $successCount = 0;
        $errors = [];

        foreach ($files as $filePath) {
            try {
                $result = $importService->importSingleKit($filePath, Auth::id(), true);
                $successCount++;
                if ($firstDna === null) {
                    $firstDna = Dna::find($result['dna_id']);
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if ($successCount === 0 || $firstDna === null) {
            Notification::make()
                ->title('DNA import failed: ' . implode('; ', $errors))
                ->danger()
                ->send();

            $this->halt();
        }

        if (!empty($errors)) {
            Notification::make()
                ->title("Imported {$successCount} kit(s) with " . count($errors) . ' error(s)')
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title("Successfully imported {$successCount} DNA kit(s)")
                ->success()
                ->send();
        }

        return $firstDna;
    }
}
