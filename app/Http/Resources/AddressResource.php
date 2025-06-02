<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'address' => $this->address,
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'telephone' => $this->telephone,
            'fax' => $this->fax,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
