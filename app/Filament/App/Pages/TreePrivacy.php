<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Models\Tree;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class TreePrivacy extends Page
{
    #[\Override]
    protected string $view = 'filament.app.pages.tree-privacy';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';

    #[\Override]
    protected static ?string $navigationLabel = 'Tree Privacy';

    #[\Override]
    protected static ?string $title = 'Tree Privacy';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📋 Research Workspace';

    /**
     * The current tenant's trees (BelongsToTenant scopes this to the active team).
     *
     * @return Collection<int, Tree>
     */
    public function trees(): Collection
    {
        return Tree::orderBy('name')->get();
    }

    /**
     * Publishing is gated at the delete tier, not the update tier.
     *
     * Making a tree public exposes records of living relatives to anyone, and
     * unlike an edit it cannot be taken back once the data has been seen or
     * indexed. That is closer in consequence to deletion than to changing a
     * field, so it sits with delete: viewers and contributors are excluded,
     * editors and administrators are not.
     *
     * Pages are not resources, so nothing about AppResource applies here and
     * the check has to be written out. Before this there was none at all — any
     * member of a team, down to a viewer invited to read one family's research,
     * could publish every tree the team held.
     */
    #[\Override]
    public static function canAccess(): bool
    {
        $team = Filament::getTenant();
        $user = auth()->user();

        return $team && $user && $user->hasTeamPermission($team, 'delete');
    }

    /**
     * Flip a tree between public and private.
     */
    public function toggle(int $treeId): void
    {
        // Re-checked here rather than trusting the page gate alone: this is a
        // public Livewire method, so it is addressable directly.
        abort_unless(static::canAccess(), 403);

        // findOrFail stays inside the tenant scope, so a foreign team's id 404s.
        $tree = Tree::findOrFail($treeId);
        $tree->is_public = ! $tree->is_public;
        $tree->save();

        Notification::make()
            ->title($tree->is_public ? 'Tree is now public' : 'Tree is now private')
            ->success()
            ->send();
    }
}
