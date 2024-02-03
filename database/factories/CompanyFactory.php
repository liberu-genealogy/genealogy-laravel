<?php

namespace Database\Factories;

use App\Models\Company;
use LaravelLiberu\Companies\Database\Factories\CompanyFactory as CoreCompanyFactory;

class CompanyFactory extends CoreCompanyFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;
}
