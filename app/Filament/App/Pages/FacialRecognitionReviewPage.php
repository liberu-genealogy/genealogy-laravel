<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class FacialRecognitionReviewPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static string $view = 'filament.app.pages.facial-recognition-review-page';

    protected static ?string $navigationLabel = 'Review Photo Tags';

    protected static ?string $title = 'Review Facial Recognition Tags';

    protected static ?string $navigationGroup = '👥 Family Tree';

    protected static ?int $navigationSort = 5;
}
