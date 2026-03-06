<?php

namespace App\Filament\App\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

trait BasicResourceTrait
{
    /**
     * Shared form components for resources that have group, gid and name.
     */
    public static function basicFormFields(): array
    {
        return [
            TextInput::make('group')->maxLength(255),
            TextInput::make('gid')->numeric(),
            TextInput::make('name')->maxLength(255),
        ];
    }

    /**
     * Shared table columns for group, gid and name.
     */
    public static function basicTableColumns(): array
    {
        return [
            TextColumn::make('group')->searchable(),
            TextColumn::make('gid')->numeric()->sortable(),
            TextColumn::make('name')->searchable(),
        ];
    }
}
