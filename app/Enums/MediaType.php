<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Taxonomy for uploaded media objects (SCOPE §8). Distinguishes the kind of
 * genealogical media so it can be filtered/displayed differently later.
 */
enum MediaType: string
{
    case PHOTO = 'photo';
    case DOCUMENT = 'document';
    case CERTIFICATE = 'certificate';
    case CENSUS = 'census';
    case SCAN = 'scan';

    public function label(): string
    {
        return match ($this) {
            self::PHOTO => 'Photo',
            self::DOCUMENT => 'Document',
            self::CERTIFICATE => 'Certificate',
            self::CENSUS => 'Census Record',
            self::SCAN => 'Scan',
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
