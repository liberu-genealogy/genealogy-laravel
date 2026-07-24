<?php

namespace App\Filament\App\Pages;

use App\Models\Dna;
use App\Services\Dna\SegmentMatcher;
use App\Services\DnaTriangulationService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DnaTriangulationPage extends Page implements HasForms
{
    use InteractsWithForms;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    #[\Override]
    protected string $view = 'filament.app.pages.dna-triangulation-page';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🧬 DNA & Matching';

    #[\Override]
    protected static ?string $navigationLabel = 'DNA Triangulation';

    #[\Override]
    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public ?array $results = null;

    #[\Override]
    public static function shouldRegisterNavigation(): bool
    {
        if (config('premium.enabled')) {
            return true;
        }

        return auth()->user()?->isPremium() ?? false;
    }

    #[\Override]
    public static function canAccess(): bool
    {
        // Server-side premium gate: hiding the nav item is not enough, the
        // page URL must reject non-premium users directly.
        if (config('premium.enabled')) {
            return true;
        }

        return auth()->user()?->isPremium() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $userId = Auth::id();
        $userKits = Dna::where('user_id', $userId)->pluck('name', 'id')->toArray();

        return $schema
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
                    ->required()
                    ->default(20)
                    // Zero asks for every pair regardless of shared DNA, which is
                    // not a triangulation. An input-sanity rule, not a correctness
                    // one — the service's comparison-performed guard already
                    // excludes uncompared pairs at any threshold.
                    //
                    // The floor is the segment matcher's minimum because a
                    // *non-zero* total is a sum of segments that each cleared it.
                    // Zero is of course reportable; it is the value being
                    // rejected. So anything in (0, 7] behaves identically to 7.
                    ->minValue(SegmentMatcher::MIN_CM)
                    ->maxValue(500)
                    ->helperText('Only show matches with at least this many shared centiMorgans (minimum '.SegmentMatcher::MIN_CM.')'),
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
                // An empty multi-select yields [], not null, and [] means
                // whereIn('id', []) — comparing against nothing at all. The
                // field's own helper text promises the opposite, so normalise
                // it here rather than leave the promise false.
                filled($data['compare_kit_ids'] ?? null) ? $data['compare_kit_ids'] : null,
                $data['min_cm']
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
