<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class ResourceModel extends Model
{
    protected $fillable = ['name', 'country_id', 'other_field_ids'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Assuming there are other dropdowns required, similar methods would be added here.
    // For example, if there's a dropdown for another resource, a similar relationship method would be defined.
}
