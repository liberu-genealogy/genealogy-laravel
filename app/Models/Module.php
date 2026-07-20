<?php

declare(strict_types=1);

namespace App\Models;

use App\Modules\ModuleManager;
use Illuminate\Database\Eloquent\Model;

/**
 * Placeholder model for the admin ModuleResource.
 *
 * Modules live on disk and are managed by {@see ModuleManager} —
 * there is no `modules` table and this model is never queried. It exists only so
 * Filament's resource/table plumbing has a model to reference for labels, keys
 * and authorization; the actual rows are supplied by the table's ->records()
 * data source (see ModuleResource::getModuleRecords()).
 */
class Module extends Model
{
    protected $table = 'modules';
}
