<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerson extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'givn'             => ['sometimes', 'required', 'string', 'max:255'],
            'surn'             => ['nullable', 'string', 'max:255'],
            'sex'              => ['nullable', 'in:M,F,U'],
            'name'             => ['nullable', 'string', 'max:255'],
            'appellative'      => ['nullable', 'string', 'max:255'],
            'email'            => ['nullable', 'email', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:50'],
            'photo_url'        => ['nullable', 'url', 'max:2048'],
            'description'      => ['nullable', 'string', 'max:5000'],
            'titl'             => ['nullable', 'string', 'max:255'],
            'npfx'             => ['nullable', 'string', 'max:255'],
            'nick'             => ['nullable', 'string', 'max:255'],
            'spfx'             => ['nullable', 'string', 'max:255'],
            'nsfx'             => ['nullable', 'string', 'max:255'],
            'birthday'         => ['nullable', 'date'],
            'birth_year'       => ['nullable', 'integer', 'min:1', 'max:9999'],
            'birth_month'      => ['nullable', 'integer', 'min:1', 'max:12'],
            'birthday_plac'    => ['nullable', 'string', 'max:255'],
            'deathday'         => ['nullable', 'date'],
            'death_year'       => ['nullable', 'integer', 'min:1', 'max:9999'],
            'death_month'      => ['nullable', 'integer', 'min:1', 'max:12'],
            'deathday_plac'    => ['nullable', 'string', 'max:255'],
            'deathday_caus'    => ['nullable', 'string', 'max:255'],
            'burial_day'       => ['nullable', 'date'],
            'burial_day_plac'  => ['nullable', 'string', 'max:255'],
            'child_in_family_id' => ['nullable', 'integer', 'exists:families,id'],
        ];
    }
}
