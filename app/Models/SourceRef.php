<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A GEDCOM SOUR reference: the record identified by (`group`, `gid`) is evidenced
 * by source `sour_id`, at `page`, with `quay` confidence and optional `text`.
 *
 * @property string|null $group
 * @property int|null $gid
 * @property int|null $sour_id
 * @property string|null $text
 * @property string|null $quay
 * @property string|null $page
 */
class SourceRef extends \FamilyTree365\LaravelGedcom\Models\SourceRef
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * `group` values. The GEDCOM importer only ever writes the fine-grained ones —
     * a source attached to a name, event, association or LDS ordinance. GROUP_INDI
     * means the source evidences the person as a whole, which only this app's UI
     * writes (CompletenessService has always counted it).
     */
    public const GROUP_INDI = 'indi';

    public const GROUP_INDI_NAME = 'indi_name';

    public const GROUP_INDI_EVEN = 'indi_even';

    /** The cited source. `sour_id` is a plain integer, no FK constraint. */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'sour_id');
    }

    /**
     * The person this reference evidences.
     *
     * Only meaningful when `group` is GROUP_INDI — `gid` is the importer's
     * pseudo-polymorphic key, so for other groups it points at an event, name or
     * ordinance row instead, and this relation would resolve to an unrelated person.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'gid');
    }

    /**
     * GEDCOM QUAY is 0-3: 0 unreliable, 1 questionable, 2 secondary, 3 primary.
     * Imported files carry free text here too, so fall back to the raw value.
     */
    public function qualityLabel(): string
    {
        return match ($this->quay) {
            '0' => 'Unreliable',
            '1' => 'Questionable',
            '2' => 'Secondary evidence',
            '3' => 'Primary evidence',
            null, '' => 'Unrated',
            default => (string) $this->quay,
        };
    }
}
