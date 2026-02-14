<?php

namespace App\Filament\App\Resources\PersonResource\RelationManagers;

use App\Models\PersonPhoto;
use App\Services\FacialRecognitionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'photos';

    protected static ?string $title = 'Photos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->label('Photo')
                    ->image()
                    ->directory('person-photos')
                    ->disk('public')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->maxLength(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Photo')
                    ->disk('public')
                    ->height(80)
                    ->width(80),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_analyzed')
                    ->label('Analyzed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('tags_count')
                    ->label('Tags')
                    ->counts('tags')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_analyzed')
                    ->label('Analyzed'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['team_id'] = auth()->user()->currentTeam?->id;
                        $data['person_id'] = $this->ownerRecord->id;
                        $data['file_name'] = basename($data['file_path']);
                        return $data;
                    })
                    ->after(function (PersonPhoto $record) {
                        $facialRecognitionService = app(FacialRecognitionService::class);
                        $result = $facialRecognitionService->analyzePhoto($record);
                        
                        if ($result['success']) {
                            Notification::make()
                                ->title('Photo analyzed')
                                ->body("Found {$result['faces_detected']} face(s)")
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('analyze')
                    ->label('Analyze')
                    ->icon('heroicon-o-camera')
                    ->color('primary')
                    ->visible(fn (PersonPhoto $record) => !$record->is_analyzed)
                    ->action(function (PersonPhoto $record) {
                        $facialRecognitionService = app(FacialRecognitionService::class);
                        $result = $facialRecognitionService->analyzePhoto($record);
                        
                        if ($result['success']) {
                            Notification::make()
                                ->title('Photo analyzed')
                                ->body("Found {$result['faces_detected']} face(s)")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Analysis failed')
                                ->body($result['error'] ?? 'Unknown error')
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (PersonPhoto $record) {
                        Storage::disk('public')->delete($record->file_path);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                Storage::disk('public')->delete($record->file_path);
                            }
                        }),
                ]),
            ]);
    }
}
