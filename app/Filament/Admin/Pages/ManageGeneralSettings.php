<?php

namespace App\Filament\Admin\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'General Settings';

    protected static ?string $navigationLabel = 'General Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site Information')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_email')
                            ->label('Site Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_phone')
                            ->label('Site Phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_address')
                            ->label('Site Address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_country')
                            ->label('Country')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_currency')
                            ->label('Currency Symbol')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('site_default_language')
                            ->label('Default Language')
                            ->maxLength(10)
                            ->default('en'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Social Media Links')
                    ->description('Add your social media profile URLs')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('github_url')
                            ->label('GitHub URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Textarea::make('footer_copyright')
                            ->label('Copyright Text')
                            ->required()
                            ->maxLength(500)
                            ->rows(2),
                    ]),
            ]);
    }
}