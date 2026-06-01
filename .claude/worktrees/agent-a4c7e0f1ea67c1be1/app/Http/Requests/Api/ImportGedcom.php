<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ImportGedcom extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:ged,gedcom,txt',
                'max:102400', // 100 MB
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'A GEDCOM file is required.',
            'file.mimes'    => 'The file must be a GEDCOM file (.ged, .gedcom, or .txt).',
            'file.max'      => 'The GEDCOM file may not exceed 100 MB.',
        ];
    }
}
