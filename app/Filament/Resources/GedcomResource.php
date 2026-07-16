<?php

namespace App\Filament\Resources;

use App\Jobs\ExportGedCom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;

class GedcomResource
{
    public static function exportGedcom(): void
    {
        $user = Auth::user();
        if ($user) {
            $file = 'gedcom_export_'.$user->id.'_'.now()->format('YmdHis').'.ged';
            Queue::push(new ExportGedCom($file, $user));
        }
    }
}
