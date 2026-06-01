<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'givn'       => $this->givn,
            'surn'       => $this->surn,
            'sex'        => $this->sex,
            'birthday'   => $this->birthday,
            'email'      => $this->email,
            'photo_url'  => $this->photo_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
