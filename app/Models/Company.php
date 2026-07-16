<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use LaravelLiberu\Companies\Models\Company as CoreCompany;

class Company extends Model
{
    // use CreatedBy;
    use HasFactory;

    #[\Override]
    protected $fillable = [
        'privacy',
        'name',
        'email',
        'is_tenant',
        'status',
        // TODO: add all the other fields from migration or verify that model exists on LaravelLiberu package
    ];
}
