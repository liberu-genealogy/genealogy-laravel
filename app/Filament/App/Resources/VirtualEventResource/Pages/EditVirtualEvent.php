<?php

namespace App\Filament\App\Resources\VirtualEventResource\Pages;

use App\Filament\App\Resources\VirtualEventResource;
use App\Services\VideoConferencingService;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditVirtualEvent extends EditRecord
{
    #[\Override]
    protected static string $resource = VirtualEventResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_meeting')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->action(function (): void {
                    try {
                        $service = app(VideoConferencingService::class);
                        $service->createMeeting($this->getRecord());

                        Notification::make()
                            ->title('Meeting Created')
                            ->body('Video conference meeting has been created successfully.')
                            ->success()
                            ->send();

                        $this->refreshFormData(['meeting_url', 'join_url', 'meeting_password']);
                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Meeting Creation Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn (): bool => empty($this->getRecord()->meeting_id) && $this->getRecord()->platform !== 'custom'),
            Action::make('update_meeting')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function (): void {
                    try {
                        $service = app(VideoConferencingService::class);
                        $service->updateMeeting($this->getRecord());

                        Notification::make()
                            ->title('Meeting Updated')
                            ->body('Video conference meeting has been updated successfully.')
                            ->success()
                            ->send();

                        $this->refreshFormData(['meeting_url', 'join_url']);
                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Meeting Update Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn (): bool => ! empty($this->getRecord()->meeting_id) && $this->getRecord()->platform !== 'custom'),
            Action::make('join_meeting')
                ->icon('heroicon-o-video-camera')
                ->color('success')
                ->url(fn () => $this->getRecord()->join_url ?? '#')
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->getRecord()->canJoin() && ! empty($this->getRecord()->join_url)),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        // Update meeting if it exists and platform supports it
        if (! empty($record->meeting_id) && $record->platform !== 'custom') {
            try {
                $service = app(VideoConferencingService::class);
                $service->updateMeeting($record);
            } catch (Exception $e) {
                Notification::make()
                    ->title('Meeting Update Failed')
                    ->body('Event saved, but failed to update video conference meeting: '.$e->getMessage())
                    ->warning()
                    ->send();
            }
        }
    }
}
