<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use LaravelLiberu\Roles\Models\Role;
use LaravelLiberu\UserGroups\Models\UserGroup;
use LaravelLiberu\Users\Database\Factories\UserFactory as CoreUserFactory;

class UserFactory extends CoreUserFactory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'person_id' => Person::factory(),
            'group_id' => UserGroup::factory(),
            'email' => fn ($attributes) => Person::find($attributes['person_id'])->email,
            'role_id' => Role::factory(),
            'is_active' => $this->faker->boolean,
        ];
    }
}
