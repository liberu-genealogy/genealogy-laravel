<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ExportGedCom;

class GedcomResource
{
    public static function exportGedcom()
    {
        $user = Auth::user();
        if ($user) {
            $file = 'gedcom_export_' . $user->id . '_' . now()->format('YmdHis') . '.ged';
            Queue::push(new ExportGedCom($file, $user));
        }
    }
}
