<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'register_number_as_in_tax_invoice' => $this->register_number_as_in_tax_invoice,
            'trc_number' => $this->trc_number,
            'register_number_related_branch' => $this->register_number_related_branch,
            'valid_until' => $this->valid_until,
            'taxable_entrepreneur_number' => $this->taxable_entrepreneur_number,
            'tax_invoice_serial_number' => $this->tax_invoice_serial_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
