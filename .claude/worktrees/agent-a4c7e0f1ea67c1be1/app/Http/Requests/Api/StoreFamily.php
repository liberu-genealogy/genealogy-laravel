<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamily extends FormRequest
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
            'husband_id'  => ['nullable', 'integer', 'exists:people,id'],
            'wife_id'     => ['nullable', 'integer', 'exists:people,id'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active'   => ['nullable', 'boolean'],
            'type_id'     => ['nullable', 'integer', 'exists:types,id'],
        ];
    }
}
