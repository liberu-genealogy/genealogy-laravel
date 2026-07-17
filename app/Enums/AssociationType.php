<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Person-to-person associations — GEDCOM's ASSO/RELA structure.
 *
 * This is where step-parent, guardianship and godparent relationships live.
 * They deliberately do NOT belong on `people.pedigree` (see PedigreeType):
 * pedigree describes how a child links to the ONE family they are a child of,
 * whereas an association is a free-standing link between two people that needs
 * no family record at all.
 *
 * GEDCOM 5.5.1 RELA is a free-text descriptor, so `person_asso.rela` stays a
 * string and imported files may carry values outside this enum. Treat these as
 * the curated set the UI offers, not a closed world — always resolve through
 * tryFrom() and fall back to the raw string.
 */
enum AssociationType: string
{
    case STEP_PARENT = 'step-parent';
    case STEP_CHILD = 'step-child';
    case GUARDIAN = 'guardian';
    case WARD = 'ward';
    case GODPARENT = 'godparent';
    case GODCHILD = 'godchild';
    case FOSTER_PARENT = 'foster-parent';
    case FOSTER_CHILD = 'foster-child';
    case WITNESS = 'witness';
    case INFORMANT = 'informant';

    public function label(): string
    {
        return match ($this) {
            self::STEP_PARENT => 'Step-parent',
            self::STEP_CHILD => 'Step-child',
            self::GUARDIAN => 'Guardian',
            self::WARD => 'Ward',
            self::GODPARENT => 'Godparent',
            self::GODCHILD => 'Godchild',
            self::FOSTER_PARENT => 'Foster parent',
            self::FOSTER_CHILD => 'Foster child',
            self::WITNESS => 'Witness',
            self::INFORMANT => 'Informant',
        };
    }

    /**
     * The reciprocal association. GEDCOM records an ASSO in one direction only,
     * so this is what the other person's side of the link means — used to label
     * incoming associations without storing a duplicate row.
     */
    public function inverse(): self
    {
        return match ($this) {
            self::STEP_PARENT => self::STEP_CHILD,
            self::STEP_CHILD => self::STEP_PARENT,
            self::GUARDIAN => self::WARD,
            self::WARD => self::GUARDIAN,
            self::GODPARENT => self::GODCHILD,
            self::GODCHILD => self::GODPARENT,
            self::FOSTER_PARENT => self::FOSTER_CHILD,
            self::FOSTER_CHILD => self::FOSTER_PARENT,
            // Witness/informant are roles, not pairings: they have no reciprocal.
            self::WITNESS => self::WITNESS,
            self::INFORMANT => self::INFORMANT,
        };
    }

    /**
     * Human label for a stored `rela` string, which may be free text from an
     * imported GEDCOM rather than one of our cases.
     */
    public static function labelFor(?string $rela): string
    {
        if ($rela === null || trim($rela) === '') {
            return 'Unknown';
        }

        return self::tryFrom($rela)?->label() ?? $rela;
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
