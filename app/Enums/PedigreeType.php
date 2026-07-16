<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * GEDCOM FAMC.PEDI — a child's pedigree link to its child_in_family family.
 * A null pedigree means the standard/unstated case, i.e. biological birth.
 *
 * Step-parent and guardianship are deliberately absent: they are not a child's
 * FAMC link type but a person->person association (GEDCOM ASSO / PersonAsso).
 */
enum PedigreeType: string
{
    case BIRTH = 'birth';
    case ADOPTED = 'adopted';
    case FOSTER = 'foster';
    case SEALING = 'sealing';

    public function label(): string
    {
        return match ($this) {
            self::BIRTH => 'Biological',
            self::ADOPTED => 'Adopted',
            self::FOSTER => 'Foster',
            self::SEALING => 'Sealing (LDS)',
        };
    }

    /**
     * value => label map for a Filament Select ->options().
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
