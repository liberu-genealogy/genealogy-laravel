<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantPerson extends Model
{
    public $timestamps = false;
    protected $casts = [
        'birthday' => 'datetime',
        'deathday' => 'datetime',
        'burial_day' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $connection = 'mysql';

    protected $guarded = ['id'];

    protected $fillable = ['tenant_person_id', 'tenant_id', 'name', 'appellative', 'nin', 'email',
        'phone', 'birthday', 'birth_month', 'birth_year', 'deathday', 'death_month', 'death_year',
        'burial_day', 'burial_month', 'burial_year', 'bank', 'bank_account', 'notes', 'gid', 'givn',
        'surn', 'uid', 'type', 'npfx', 'nick', 'spfx', 'nsfx', 'sex', 'description',
        'child_in_family_id', 'chan', 'rin', 'resn', 'rfn', 'afn', 'birthday_dati',
        'birthday_plac', 'deathday_dati', 'deathday_plac', 'deathday_caus', 'burial_day_dati',
        'burial_day_plac', 'famc', 'fams', 'titl', 'chr', 'created_at', 'updated_at'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'tenant_id', 'id');
    }

    public function systemPerson()
    {
        return $this->belongsTo(SystemPerson::class, 'tenant_id', 'id');
    }
}
