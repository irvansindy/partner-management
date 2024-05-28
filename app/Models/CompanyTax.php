<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyTax extends Model
{
    use HasFactory;
    protected $table = "company_taxes";
    protected $fillable = [
        'company_id',
        'register_number_as_in_tax_invoice',
        'trc_number',
        'register_number_related_branch',
        'valid_until',
        'taxable_entrepreneur_number',
        'tax_invoice_serial_number',
    ];
    protected $hidden = [];
}
