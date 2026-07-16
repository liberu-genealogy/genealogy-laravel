<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\EventType;
use PHPUnit\Framework\TestCase;

class EventTypeTest extends TestCase
{
    /** The 10 SCOPE event types, each mapped to its stored value + label. */
    public function test_it_covers_all_ten_scope_event_types(): void
    {
        $expected = [
            'BIRT' => 'Birth',
            'BAPM' => 'Baptism',
            'MARR' => 'Marriage',
            'DIV' => 'Divorce',
            'DEAT' => 'Death',
            'BURI' => 'Burial',
            'IMMI' => 'Immigration',
            'CENS' => 'Census',
            '_MILT' => 'Military Service',
            'OCCU' => 'Occupation',
        ];

        foreach ($expected as $value => $label) {
            $case = EventType::tryFrom($value);
            $this->assertNotNull($case, "Missing EventType case for stored value {$value}");
            $this->assertSame($label, $case->label());
        }
    }

    public function test_options_map_stored_values_to_labels(): void
    {
        $options = EventType::options();

        $this->assertSame('Birth', $options['BIRT']);
        $this->assertSame('Occupation', $options['OCCU']);
        $this->assertCount(count(EventType::cases()), $options);
    }

    public function test_family_subset_is_marriage_and_divorce_only(): void
    {
        $familyValues = array_map(fn (EventType $c): string => $c->value, EventType::familyCases());
        sort($familyValues);
        $this->assertSame(['DIV', 'MARR'], $familyValues);

        // Person subset is the complement; must exclude the family events, include births.
        $personValues = array_map(fn (EventType $c): string => $c->value, EventType::personCases());
        $this->assertNotContains('MARR', $personValues);
        $this->assertNotContains('DIV', $personValues);
        $this->assertContains('BIRT', $personValues);
    }

    public function test_values_returns_all_backing_values(): void
    {
        $this->assertCount(10, EventType::values());
        $this->assertContains('OCCU', EventType::values());
    }
}
