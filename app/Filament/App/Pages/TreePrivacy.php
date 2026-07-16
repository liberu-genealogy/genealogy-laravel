<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Models\Tree;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class TreePrivacy extends Page
{
    #[\Override]
    protected string $view = 'filament.app.pages.tree-privacy';

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-lock-closed';

    #[\Override]
    protected static ?string $navigationLabel = 'Tree Privacy';

    #[\Override]
    protected static ?string $title = 'Tree Privacy';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '📋 Research Management';

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
     * Flip a tree between public and private.
     */
    public function toggle(int $treeId): void
    {
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
