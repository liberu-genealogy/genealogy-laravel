<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalPlan extends Model
{
    use HasFactory;

    #[\Override]
    protected $guarded = ['id'];

    public function paypalSubscription()
    {
        return $this->hasOne(PaypalSubscription::class, 'paypal_plan_id', 'paypal_id');
    }
}
