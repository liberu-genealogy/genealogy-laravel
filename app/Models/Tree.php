<?php

namespace App\Models;

use App\Modules\Tree\Services\TreeBuilderService;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tree extends Model
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * @var array
     */
    #[\Override]
    protected $fillable = [
        'team_id',
        'user_id',
        'name',
        'description',
        'root_person_id',
        'is_public',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Private by default at the model level too, not only via the DB default —
     * so a freshly-instantiated Tree reads as private before it round-trips.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_public' => false,
    ];

    /**
     * trees.user_id is NOT NULL with no database default. Stamp the
     * authenticated owner when a create omits it — mirroring how
     * BelongsToTenant stamps team_id — so an import or form that forgets
     * user_id fails safe instead of throwing "Field 'user_id' doesn't have a
     * default value" (issue #1548). An explicit user_id (e.g. an admin acting
     * for another user) is left untouched.
     *
     * Overrides boot(), not booted(): BelongsToTenant owns booted() for the
     * global scope + team_id stamping, and a class-level booted() would shadow
     * the trait's copy. boot() is a separate hook, so both run.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $tree): void {
            if (empty($tree->user_id) && auth()->check()) {
                $tree->user_id = auth()->id();
            }
        });
    }

    /**
     * Only trees the owner has opted to share publicly.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Trees kept private (the default).
     */
    public function scopePrivate(Builder $query): Builder
    {
        return $query->where('is_public', false);
    }

    /**
     * Get the user that owns the tree.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the root person of the tree.
     */
    public function rootPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'root_person_id');
    }

    /**
     * Get statistics for this tree.
     */
    public function getStats(): array
    {
        if (! $this->rootPerson) {
            return [
                'total_people' => 0,
                'total_ancestors' => 0,
                'total_descendants' => 0,
                'total_generations' => 0,
            ];
        }

        $treeService = app(TreeBuilderService::class);

        $ancestors = $treeService->getAllAncestors($this->rootPerson);
        $descendants = $treeService->getAllDescendants($this->rootPerson);

        // Calculate total unique people
        $allPeople = collect([$this->rootPerson])
            ->merge($ancestors)
            ->merge($descendants)
            ->unique('id');

        return [
            'total_people' => $allPeople->count(),
            'total_ancestors' => $ancestors->count(),
            'total_descendants' => $descendants->count(),
            'total_generations' => $this->calculateTreeDepth(),
        ];
    }

    /**
     * Calculate the depth of the tree (maximum generations).
     */
    private function calculateTreeDepth(): int
    {
        if (! $this->rootPerson) {
            return 0;
        }

        $treeService = app(TreeBuilderService::class);

        // Get max ancestor depth
        $maxAncestorDepth = $this->getAncestorDepth($this->rootPerson);

        // Get max descendant depth
        $maxDescendantDepth = $this->getDescendantDepth($this->rootPerson);

        return $maxAncestorDepth + $maxDescendantDepth;
    }

    /**
     * Calculate ancestor depth recursively.
     */
    private function getAncestorDepth(Person $person, int $depth = 0): int
    {
        if (! $person->childInFamily) {
            return $depth;
        }

        $maxDepth = $depth;

        if ($person->childInFamily->husband) {
            $maxDepth = max($maxDepth, $this->getAncestorDepth($person->childInFamily->husband, $depth + 1));
        }

        if ($person->childInFamily->wife) {
            return max($maxDepth, $this->getAncestorDepth($person->childInFamily->wife, $depth + 1));
        }

        return $maxDepth;
    }

    /**
     * Calculate descendant depth recursively.
     */
    private function getDescendantDepth(Person $person, int $depth = 0): int
    {
        $families = $person->familiesAsHusband->merge($person->familiesAsWife);

        if ($families->isEmpty()) {
            return $depth;
        }

        $maxDepth = $depth;

        foreach ($families as $family) {
            $children = Person::where('child_in_family_id', $family->id)->get();

            foreach ($children as $child) {
                $maxDepth = max($maxDepth, $this->getDescendantDepth($child, $depth + 1));
            }
        }

        return $maxDepth;
    }
}
