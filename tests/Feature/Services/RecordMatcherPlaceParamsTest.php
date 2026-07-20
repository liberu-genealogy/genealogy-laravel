<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Person;
use App\Models\User;
use App\Services\RecordMatcher\Providers\AncestryProvider;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
use App\Services\RecordMatcher\Providers\MyHeritageProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Every provider guarded its place parameter on $person->birthplace /
 * ->deathplace. Neither is a column or a relation — the GEDCOM names are
 * birthday_plac / deathday_plac — and Eloquent returns null for an unknown
 * attribute rather than throwing, so the guards were silently always false and
 * no provider search ever sent a birth or death place. Nothing failed; matches
 * were just quietly worse.
 *
 * buildSearchParams() is protected and its public caller performs a real HTTP
 * search, so these drive it by reflection.
 *
 * @return array<string, array{0: class-string, 1: string, 2: string}>
 */
class RecordMatcherPlaceParamsTest extends TestCase
{
    use RefreshDatabase;

    public static function providerCases(): array
    {
        return [
            'ancestry' => [AncestryProvider::class, 'birthLocation', 'deathLocation'],
            'familysearch' => [FamilySearchProvider::class, 'birthPlace', 'deathPlace'],
            'myheritage' => [MyHeritageProvider::class, 'birth_place', 'death_place'],
        ];
    }

    #[DataProvider('providerCases')]
    public function test_provider_sends_the_persons_birth_and_death_place(
        string $providerClass,
        string $birthKey,
        string $deathKey
    ): void {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $person = Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'birthday_plac' => 'Manchester, England',
            'deathday_plac' => 'Leeds, England',
        ]);

        $method = new ReflectionMethod($providerClass, 'buildSearchParams');
        $method->setAccessible(true);

        $params = $method->invoke(new $providerClass, $person);

        $this->assertSame('Manchester, England', $params[$birthKey] ?? null);
        $this->assertSame('Leeds, England', $params[$deathKey] ?? null);
    }

    /**
     * @return array<string, array{0: class-string, 1: string, 2: string}>
     */
    public static function nameCases(): array
    {
        return [
            'ancestry' => [AncestryProvider::class, 'givenName', 'surname'],
            'familysearch' => [FamilySearchProvider::class, 'givenName', 'surname'],
            'myheritage' => [MyHeritageProvider::class, 'first_name', 'last_name'],
        ];
    }

    /**
     * The name guards read $person->first_name/last_name, which GEDCOM import
     * never populates (the columns are givn/surn), so no provider ever sent a
     * name to search — the same silent-null failure as the place params above.
     */
    #[DataProvider('nameCases')]
    public function test_provider_sends_the_persons_given_and_surname(
        string $providerClass,
        string $givenKey,
        string $surnameKey
    ): void {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $person = Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
        ]);

        $method = new ReflectionMethod($providerClass, 'buildSearchParams');
        $method->setAccessible(true);

        $params = $method->invoke(new $providerClass, $person);

        $this->assertSame('Ada', $params[$givenKey] ?? null);
        $this->assertSame('Lovelace', $params[$surnameKey] ?? null);
    }

    #[DataProvider('providerCases')]
    public function test_provider_omits_place_when_the_person_has_none(
        string $providerClass,
        string $birthKey,
        string $deathKey
    ): void {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $person = Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'birthday_plac' => null,
            'deathday_plac' => null,
        ]);

        $method = new ReflectionMethod($providerClass, 'buildSearchParams');
        $method->setAccessible(true);

        $params = $method->invoke(new $providerClass, $person);

        $this->assertArrayNotHasKey($birthKey, $params);
        $this->assertArrayNotHasKey($deathKey, $params);
    }
}
