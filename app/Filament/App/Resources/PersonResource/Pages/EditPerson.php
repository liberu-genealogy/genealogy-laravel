<?php

namespace App\Filament\App\Resources\PersonResource\Pages;

use App\Filament\App\Resources\PersonResource;
use App\Models\MediaObject;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPerson extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('selectMedia')
                ->label('Select GEDCOM Media')
                // 'photograph' is a Heroicons v1 name; v2 renamed it to 'photo', and
                // blade-icons throws SvgNotFound at render rather than degrading.
                ->icon('heroicon-o-photo')
                ->modalHeading('Select GEDCOM Media to Use as Profile Photo')
                ->modalWidth('lg')
                ->form([
                    Select::make('media_id')
                        ->label('GEDCOM Media')
                        ->options(fn () => MediaObject::orderBy('id', 'desc')->pluck('titl', 'id')->toArray())
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $mediaId = $data['media_id'] ?? null;
                    if (! $mediaId) {
                        Notification::make()->title('No media selected.')->danger()->send();

                        return;
                    }

                    $media = MediaObject::with('files')->find($mediaId);
                    if (! $media) {
                        Notification::make()->title('Selected media not found.')->danger()->send();

                        return;
                    }

                    // Try to find an associated file record; use its `medi` field as the path/URL.
                    $file = $media->files->first();
                    $filePath = $file?->medi ?? null;

                    if (! $filePath) {
                        Notification::make()->title('Selected media has no associated file path.')->danger()->send();

                        return;
                    }

                    $record = $this->getRecord();
                    // Normalize the file path/URL before storing in photo_url
                    $url = $filePath;
                    try {
                        $startsWithHttp = str_starts_with(strtolower($filePath), 'http://') || str_starts_with(strtolower($filePath), 'https://');
                    } catch (\Throwable) {
                        $startsWithHttp = false;
                    }

                    if ($startsWithHttp || str_starts_with($filePath, '/')) {
                        $url = $filePath;
                    } else {
                        // If it exists on the public disk, build a public URL
                        try {
                            $disk = Storage::disk('public');
                            if ($disk->exists($filePath)) {
                                $url = $disk->url($filePath);
                            }
                        } catch (\Throwable) {
                            // leave original value if disk check fails
                        }
                    }

                    $record->photo_url = $url;
                    $record->save();

                    Notification::make()->title('Person photo updated from GEDCOM media.')->success()->send();
                    // Refresh the page to show updated image in the form/table.
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                }),
        ];
    }
}
