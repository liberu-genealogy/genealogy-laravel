<?php

namespace App\Filament\App\Resources\PersonResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\MediaObject;

class EditPerson extends EditRecord
{
    protected static string $resource = PersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('selectMedia')
                ->label('Select GEDCOM Media')
                ->icon('heroicon-o-photograph')
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
                        $this->notify('danger', 'No media selected.');
                        return;
                    }

                    $media = MediaObject::with('files')->find($mediaId);
                    if (! $media) {
                        $this->notify('danger', 'Selected media not found.');
                        return;
                    }

                    // Try to find an associated file record; use its `medi` field as the path/URL.
                    $file = $media->files->first();
                    $filePath = $file?->medi ?? null;

                    if (! $filePath) {
                        $this->notify('danger', 'Selected media has no associated file path.');
                        return;
                    }

                    $record = $this->getRecord();
                    // Normalize the file path/URL before storing in photo_url
                    $url = $filePath;
                    try {
                        $startsWithHttp = str_starts_with(strtolower($filePath), 'http://') || str_starts_with(strtolower($filePath), 'https://');
                    } catch (\Throwable $e) {
                        $startsWithHttp = false;
                    }

                    if ($startsWithHttp || str_starts_with($filePath, '/')) {
                        $url = $filePath;
                    } else {
                        // If it exists on the public disk, build a public URL
                        try {
                            $disk = \Illuminate\Support\Facades\Storage::disk('public');
                            if ($disk->exists($filePath)) {
                                $url = $disk->url($filePath);
                            }
                        } catch (\Throwable $e) {
                            // leave original value if disk check fails
                        }
                    }

                    $record->photo_url = $url;
                    $record->save();

                    $this->notify('success', 'Person photo updated from GEDCOM media.');
                    // Refresh the page to show updated image in the form/table.
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                }),
        ];
    }
}
