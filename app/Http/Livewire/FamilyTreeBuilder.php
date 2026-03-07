<?php

namespace App\Http\Livewire;

use App\Models\Person;
use App\Models\Family;
use Livewire\Component;
use Livewire\Attributes\On;

final class FamilyTreeBuilder extends Component
{
    use \App\Traits\FamilyTreeBuilderTrait;

    public function render()
    {
        return view('livewire.family-tree-builder');
    }
}
