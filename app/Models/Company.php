<?php

namespace App\Models;

use App\Traits\CreatedBy;
use LaravelLiberu\Companies\Models\Company as CoreCompany;

class Company extends CoreCompany
{
    use CreatedBy;

    protected $fillable = [
        'privacy',
        'name',
        'email',
        'is_tenant',
        'status',
    ];
}
