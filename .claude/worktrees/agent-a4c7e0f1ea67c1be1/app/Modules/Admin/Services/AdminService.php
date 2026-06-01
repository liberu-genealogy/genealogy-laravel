<?php

namespace App\Modules\Admin\Services;

use App\Models\Chan;
use App\Models\Type;
use App\Models\Refn;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    /**
     * Get system statistics.
     */
    public function getSystemStatistics(): array
    {
        return [
            'total_changes' => Chan::count(),
            'total_types' => Type::count(),
            'total_references' => Refn::count(),
            'recent_changes' => $this->getRecentChanges(10),
            'active_types' => Type::where('is_active', true)->count(),
        ];
    }

    /**
     * Get recent changes.
     */
    public function getRecentChanges(int $limit = 10): Collection
    {
        return Chan::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all types.
     */
    public function getAllTypes(): Collection
    {
        return Type::orderBy('name')->get();
    }

    /**
     * Get active types.
     */
    public function getActiveTypes(): Collection
    {
        return Type::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Create a new type.
     */
    public function createType(array $data): Type
    {
        return Type::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update type.
     */
    public function updateType(Type $type, array $data): Type
    {
        $type->update($data);
        return $type->fresh();
    }

    /**
     * Delete type.
     */
    public function deleteType(Type $type): bool
    {
        return $type->delete();
    }

    /**
     * Log system change.
     */
    public function logChange(string $group, string $gid, array $data = []): Chan
    {
        return Chan::create([
            'group' => $group,
            'gid' => $gid,
            'date' => now()->format('Y-m-d'),
            'time' => now()->format('H:i:s'),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Get changes for specific group.
     */
    public function getChangesByGroup(string $group): Collection
    {
        return Chan::where('group', $group)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Clean old changes.
     */
    public function cleanOldChanges(int $days = 365): int
    {
        $cutoffDate = now()->subDays($days);
        return Chan::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Export admin data.
     */
    public function exportAdminData(): array
    {
        return [
            'statistics' => $this->getSystemStatistics(),
            'types' => $this->getAllTypes()->toArray(),
            'recent_changes' => $this->getRecentChanges(50)->toArray(),
        ];
    }
}