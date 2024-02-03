<?php

namespace App\Models;

class SystemPerson extends Person
{
    protected $table = 'people';

    public function getConnectionName(): string
    {
        return 'mysql';
    }
}
