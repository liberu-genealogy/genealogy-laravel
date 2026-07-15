<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * @param  bool  $bare  Render the slot without wrapping it in <main>, for
     *                      layouts that supply their own header/main/footer.
     *                      Without this the marketing layout nests a <main>
     *                      inside this one and puts <header>/<footer> in it.
     */
    public function __construct(public bool $bare = false) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
