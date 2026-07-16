<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enumerated genealogy event types.
 *
 * The backing value is the stored value: a GEDCOM 5.5.1 tag where one exists.
 * In this app the event type is stored in the events' `title` column (see
 * Event::getTitle() / PersonEvent::$gedcom_event_names / EventsService), not the
 * `type` column, which is GEDCOM's free-text TYPE sub-qualifier.
 *
 * ponytail: military has no standard GEDCOM 5.5.1 event tag; `_MILT` is the
 * de-facto custom tag genealogy tools use. Swap it if the import layer settles
 * on a different one.
 */
enum EventType: string
{
    // Person-level events.
    case Birth = 'BIRT';
    case Baptism = 'BAPM';
    case Death = 'DEAT';
    case Burial = 'BURI';
    case Immigration = 'IMMI';
    case Census = 'CENS';
    case Military = '_MILT';
    case Occupation = 'OCCU';

    // Family-level events.
    case Marriage = 'MARR';
    case Divorce = 'DIV';

    public function label(): string
    {
        return match ($this) {
            self::Birth => 'Birth',
            self::Baptism => 'Baptism',
            self::Death => 'Death',
            self::Burial => 'Burial',
            self::Immigration => 'Immigration',
            self::Census => 'Census',
            self::Military => 'Military Service',
            self::Occupation => 'Occupation',
            self::Marriage => 'Marriage',
            self::Divorce => 'Divorce',
        };
    }

    /** Family-level events belong on FamilyEvent; everything else is person-level. */
    public function isFamily(): bool
    {
        return in_array($this, [self::Marriage, self::Divorce], true);
    }

    /** @return list<self> */
    public static function personCases(): array
    {
        return array_values(array_filter(self::cases(), fn (self $c): bool => ! $c->isFamily()));
    }

    /** @return list<self> */
    public static function familyCases(): array
    {
        return array_values(array_filter(self::cases(), fn (self $c): bool => $c->isFamily()));
    }

    /**
     * value => label map for a Filament Select ->options(). Pass a subset
     * (e.g. self::personCases()) to scope it; defaults to every case.
     *
     * @param  list<self>|null  $cases
     * @return array<string, string>
     */
    public static function options(?array $cases = null): array
    {
        $cases ??= self::cases();

        $options = [];
        foreach ($cases as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    /** @return list<string> backing (stored) values */
    public static function values(): array
    {
        return array_map(fn (self $c): string => $c->value, self::cases());
    }
}
