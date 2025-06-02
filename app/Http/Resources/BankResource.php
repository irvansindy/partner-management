<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'branch' => $this->branch,
            'account_name' => $this->account_name,
            'city_or_country' => $this->city_or_country,
            'account_number' => $this->account_number,
            'currency' => $this->currency,
            'swift_code' => $this->swift_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
