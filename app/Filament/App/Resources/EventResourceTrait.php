<?php

namespace App\Filament\App\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

trait EventResourceTrait
{
    public static function eventFormFields(): array
    {
        return [
            TextInput::make('converted_date')->maxLength(255),
            TextInput::make('year')->numeric(),
            TextInput::make('month')->numeric(),
            TextInput::make('day')->numeric(),
            TextInput::make('type')->maxLength(255),
            Textarea::make('description')->maxLength(255),
            TextInput::make('plac')->maxLength(255),
            TextInput::make('addr_id')->numeric(),
            TextInput::make('phon')->maxLength(255),
            Textarea::make('caus')->maxLength(65535)->columnSpanFull(),
            TextInput::make('age')->maxLength(255),
            TextInput::make('agnc')->maxLength(255),
            TextInput::make('title')->maxLength(255),
            TextInput::make('date')->maxLength(255),
            TextInput::make('places_id')->numeric(),
        ];
    }

    public static function eventTableColumns(): array
    {
        return [
            TextColumn::make('converted_date')->searchable(),
            TextColumn::make('year')->numeric()->sortable(),
            TextColumn::make('month')->numeric()->sortable(),
            TextColumn::make('day')->numeric()->sortable(),
            TextColumn::make('type')->searchable(),
            TextColumn::make('plac')->searchable(),
            TextColumn::make('addr_id')->numeric()->sortable(),
            TextColumn::make('phon')->searchable(),
            TextColumn::make('age')->searchable(),
            TextColumn::make('agnc')->searchable(),
            TextColumn::make('title')->searchable(),
            TextColumn::make('date')->searchable(),
            TextColumn::make('description')->searchable(),
            TextColumn::make('places_id')->numeric()->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('deleted_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
