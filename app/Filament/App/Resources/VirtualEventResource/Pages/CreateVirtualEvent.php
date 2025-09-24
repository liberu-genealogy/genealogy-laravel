<?php

namespace App\Filament\App\Resources\VirtualEventResource\Pages;

use App\Filament\App\Resources\VirtualEventResource;
use App\Services\VideoConferencingService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateVirtualEvent extends CreateRecord
{
    protected static string $resource = VirtualEventResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        // Auto-create meeting if platform is configured and event is published
        if ($record->status === 'published' && $record->platform !== 'custom') {
            try {
                $service = app(VideoConferencingService::class);

                if ($service->isPlatformConfigured($record->platform)) {
                    $service->createMeeting($record);

                    Notification::make()
                        ->title('Event Created')
                        ->body('Virtual event and video conference meeting have been created successfully.')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Event Created')
                        ->body('Virtual event created, but video conferencing platform is not configured. Please set up the meeting manually.')
                        ->warning()
                        ->send();
                }
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Event Created')
                    ->body('Virtual event created, but failed to create video conference meeting: ' . $e->getMessage())
                    ->warning()
                    ->send();
            }
        }
    }
}