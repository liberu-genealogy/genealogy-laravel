<?php

namespace App\Filament\App\Pages;

use App\Models\Dna;
use App\Services\DnaTriangulationService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class DnaTriangulationPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.app.pages.dna-triangulation-page';

    protected static ?string $navigationGroup = '🧬 DNA & Genetics';

    protected static ?string $navigationLabel = 'DNA Triangulation';

    protected static ?int $navigationSort = 3;

    public ?array $data = [];
    public ?array $results = null;

    public static function shouldRegisterNavigation(): bool
    {
        if (config('premium.enabled')) {
            return true;
        }
        return auth()->user()?->isPremium() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $userId = Auth::id();
        $userKits = Dna::where('user_id', $userId)->pluck('name', 'id')->toArray();

        return $form
            ->schema([
                Select::make('base_kit_id')
                    ->label('Base DNA Kit')
                    ->options($userKits)
                    ->required()
                    ->helperText('Select the primary DNA kit to match against others'),
                
                Select::make('compare_kit_ids')
                    ->label('Compare Against Kits (Optional)')
                    ->options($userKits)
                    ->multiple()
                    ->helperText('Leave empty to compare against all kits'),
                
                TextInput::make('min_cm')
                    ->label('Minimum cM Threshold')
                    ->numeric()
                    ->default(20)
                    ->minValue(0)
                    ->maxValue(500)
                    ->helperText('Only show matches with at least this many shared centiMorgans'),
            ])
            ->statePath('data');
    }

    public function runTriangulation(): void
    {
        $data = $this->form->getState();

        try {
            $triangulationService = app(DnaTriangulationService::class);

            $this->results = $triangulationService->triangulateOneAgainstMany(
                $data['base_kit_id'],
                $data['compare_kit_ids'] ?? null,
                $data['min_cm'] ?? 20.0
            );

            // Store results in database
            $triangulationService->storeTriangulationResults($this->results, 'one_to_many');

            Notification::make()
                ->title('Triangulation Complete')
                ->success()
                ->body("Found {$this->results['significant_matches']} significant matches")
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Triangulation Failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    public function getMatches(): array
    {
        return $this->results['matches'] ?? [];
    }

    public function getBaseKit(): ?array
    {
        return $this->results['base_kit'] ?? null;
    }

    public function hasResults(): bool
    {
        return $this->results !== null;
    }
}
