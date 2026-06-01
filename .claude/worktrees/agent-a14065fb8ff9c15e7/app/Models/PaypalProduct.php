<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalProduct extends Model
{
    use HasFactory;

    #[\Override]
    protected $guarded = ['id'];
}
