<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_link_id',
        'company_type',
        'company_name',
        'company_group_name',
        'established_year',
        'total_employee',
        'liable_persons',
        'business_classification',
        'business_classification_detail',
        'register_number_as_in_tax_invoice',
        'website_address',
        'system_management',
        'email_address',
        'credit_limit',
        'term_of_payment',
        'contacts',
        'addresses',
        'banks',
        'survey_data',
        'attachments',
        'ip_address',
        'user_agent',
        'submitted_at'
    ];

    protected $casts = [
        'liable_persons' => 'array',
        'contacts' => 'array',
        'addresses' => 'array',
        'banks' => 'array',
        'survey_data' => 'array',
        'attachments' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function formLink()
    {
        return $this->belongsTo(FormLink::class);
    }
}
