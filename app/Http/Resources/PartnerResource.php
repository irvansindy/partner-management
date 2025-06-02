<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'group_name' => $this->group_name,
            'type' => $this->type,
            'established_year' => $this->established_year,
            'total_employee' => $this->total_employee,
            'liable_person_and_position' => $this->liable_person_and_position,
            'owner_name' => $this->owner_name,
            'board_of_directors' => $this->board_of_directors,
            'major_shareholders' => $this->major_shareholders,
            'business_classification' => $this->business_classification,
            'business_classification_detail' => $this->business_classification_detail,
            'other_business' => $this->other_business,
            'website_address' => $this->website_address,
            'system_management' => $this->system_management,
            'contact_person' => $this->contact_person,
            'communication_language' => $this->communication_language,
            'email_address' => $this->email_address,
            'remark' => $this->remark,
            'signature' => $this->signature,
            'stamp' => $this->stamp,
            'supplier_number' => $this->supplier_number,
            'status' => $this->status,
            'location_id' => $this->location_id,
            'department_id' => $this->department_id,
            'blacklist' => $this->blacklist,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'address' => $this->whenLoaded('address', function () {
                return $this->address->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company_id' => $item->company_id,
                        'address' => $item->address,
                        'country' => $item->country,
                        'province' => $item->province,
                        'city' => $item->city,
                        'zip_code' => $item->zip_code,
                        'telephone' => $item->telephone,
                        'fax' => $item->fax,
                    ];
                });
            }),

            'bank' => $this->whenLoaded('bank', function () {
                return $this->bank->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company_id' => $item->company_id,
                        'name' => $item->name,
                        'branch' => $item->branch,
                        'account_name' => $item->account_name,
                        'city_or_country' => $item->city_or_country,
                        'account_number' => $item->account_number,
                        'currency' => $item->currency,
                        'swift_code' => $item->swift_code,
                    ];
                });
            }),

            'tax' => $this->whenLoaded('tax', function () {
                return $this->tax->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company_id' => $item->company_id,
                        'register_number_as_in_tax_invoice' => $item->register_number_as_in_tax_invoice,
                        'trc_number' => $item->trc_number,
                        'register_number_related_branch' => $item->register_number_related_branch,
                        'valid_until' => $item->valid_until,
                        'taxable_entrepreneur_number' => $item->taxable_entrepreneur_number,
                        'tax_invoice_serial_number' => $item->tax_invoice_serial_number,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                });
            }),

            'attachment' => $this->whenLoaded('attachment', function () {
                return $this->attachment->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'company_id' => $item->company_id,
                        'file_name' => $item->file_name ?? null,
                        'file_path' => $item->file_path ?? null,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                });
            }),
        ];
    }
}
