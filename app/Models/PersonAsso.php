<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AssociationType;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A GEDCOM ASSO: person `gid` is associated with person `indi`, and `rela`
 * describes how (godparent, guardian, step-parent...).
 *
 * @property string|null $group
 * @property int|null $gid
 * @property string|null $indi
 * @property string|null $rela
 * @property int $import_confirm
 */
class PersonAsso extends \FamilyTree365\LaravelGedcom\Models\PersonAsso
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    /** `group` value marking an association hanging off an individual. */
    public const GROUP_INDI = 'indi';

    /**
     * The person this association belongs to — the subject.
     *
     * `gid` is a plain integer with no FK constraint; it is the GEDCOM importer's
     * pseudo-polymorphic key, meaningful only alongside `group`.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'gid');
    }

    /**
     * The person on the other end of the association.
     *
     * `indi` is a varchar because the importer parks the raw GEDCOM xref ("@I5@")
     * there and resolves it to a person id in a later pass. Rows still holding an
     * unresolved xref (import_confirm = 0) resolve to null here rather than
     * erroring — MySQL coerces the varchar for the comparison.
     */
    public function associate(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'indi');
    }

    /**
     * The association type, or null when `rela` holds free text from an imported
     * GEDCOM that is outside our curated set (RELA is free text in the spec).
     */
    public function type(): ?AssociationType
    {
        return $this->rela === null ? null : AssociationType::tryFrom($this->rela);
    }

    public function typeLabel(): string
    {
        return AssociationType::labelFor($this->rela);
    }

    /** True once `indi` holds a resolved person id rather than a GEDCOM xref. */
    public function isResolved(): bool
    {
        return $this->import_confirm === 1;
    }
}
