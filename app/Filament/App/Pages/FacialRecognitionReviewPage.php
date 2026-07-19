<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class FacialRecognitionReviewPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    #[\Override]
    protected string $view = 'filament.app.pages.facial-recognition-review-page';

    #[\Override]
    protected static ?string $navigationLabel = 'Review Photo Tags';

    #[\Override]
    protected static ?string $title = 'Review Facial Recognition Tags';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📋 Research Workspace';

    #[\Override]
    protected static ?int $navigationSort = 5;
}
