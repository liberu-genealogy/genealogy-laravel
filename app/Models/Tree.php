<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tree extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'root_person_id',
    ];

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
        if (!$this->rootPerson) {
            return [
                'total_people' => 0,
                'total_ancestors' => 0,
                'total_descendants' => 0,
                'total_generations' => 0,
            ];
        }

        $treeService = app(\App\Modules\Tree\Services\TreeBuilderService::class);
        
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
        if (!$this->rootPerson) {
            return 0;
        }

        $treeService = app(\App\Modules\Tree\Services\TreeBuilderService::class);
        
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
        if (!$person->childInFamily) {
            return $depth;
        }

        $maxDepth = $depth;

        if ($person->childInFamily->husband) {
            $maxDepth = max($maxDepth, $this->getAncestorDepth($person->childInFamily->husband, $depth + 1));
        }

        if ($person->childInFamily->wife) {
            $maxDepth = max($maxDepth, $this->getAncestorDepth($person->childInFamily->wife, $depth + 1));
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
