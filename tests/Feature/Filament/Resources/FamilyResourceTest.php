<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\FamilyResource;
use App\Filament\App\Resources\FamilyResource\Pages\CreateFamily;
use App\Filament\App\Resources\FamilyResource\Pages\ListFamilies;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FamilyResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_resource_has_correct_model(): void
    {
        $this->assertSame(Family::class, FamilyResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(FamilyResource::getNavigationLabel());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = FamilyResource::getPages();

        $this->assertArrayHasKey('index', $pages);
    }

    public function test_family_can_be_created_in_database(): void
    {
        $family = Family::factory()->create();

        $this->assertDatabaseHas('families', ['id' => $family->id]);
    }

    /** The form's parent Selects are optional: a single-parent family saves with one slot blank. */
    public function test_single_parent_family_can_be_created_via_form(): void
    {
        $this->actingAs($this->user);
        Filament::setTenant($this->user->currentTeam, isQuiet: true);
        $parent = Person::factory()->create();

        Livewire::test(CreateFamily::class)
            ->fillForm(['husband_id' => $parent->id, 'wife_id' => null])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('families', ['husband_id' => $parent->id, 'wife_id' => null]);
    }

    /** The parent Selects apply no sex filter: a same-sex couple (two males) saves. */
    public function test_same_sex_family_can_be_created_via_form(): void
    {
        $this->actingAs($this->user);
        Filament::setTenant($this->user->currentTeam, isQuiet: true);
        $one = Person::factory()->create(['sex' => 'M']);
        $two = Person::factory()->create(['sex' => 'M']);

        Livewire::test(CreateFamily::class)
            ->fillForm(['husband_id' => $one->id, 'wife_id' => $two->id])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('families', ['husband_id' => $one->id, 'wife_id' => $two->id]);
    }

    /** The list shows parent names (not ids) and each family's children. */
    public function test_table_shows_parent_names_and_children(): void
    {
        $this->actingAs($this->user);
        Filament::setTenant($this->user->currentTeam, isQuiet: true);
        $husband = Person::factory()->create(['givn' => 'John', 'surn' => 'Doe']);
        $wife = Person::factory()->create(['givn' => 'Jane', 'surn' => 'Doe']);
        $family = Family::factory()->create(['husband_id' => $husband->id, 'wife_id' => $wife->id]);
        Person::factory()->create(['givn' => 'Kid', 'surn' => 'Doe', 'child_in_family_id' => $family->id]);

        Livewire::test(ListFamilies::class)
            ->assertTableColumnStateSet('parent1', 'John Doe', record: $family)
            ->assertTableColumnStateSet('parent2', 'Jane Doe', record: $family)
            ->assertTableColumnStateSet('children', ['Kid Doe'], record: $family);
    }

    /** A single-parent family renders the empty slot as a placeholder, not "0" or a crash. */
    public function test_table_renders_single_parent_family_cleanly(): void
    {
        $this->actingAs($this->user);
        Filament::setTenant($this->user->currentTeam, isQuiet: true);
        $husband = Person::factory()->create(['givn' => 'Solo', 'surn' => 'Parent']);
        $family = Family::factory()->create(['husband_id' => $husband->id, 'wife_id' => null]);

        Livewire::test(ListFamilies::class)
            ->assertTableColumnStateSet('parent1', 'Solo Parent', record: $family)
            ->assertTableColumnStateSet('parent2', '', record: $family);
    }
}
