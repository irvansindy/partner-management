<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySupportingDocument extends Model
{
    use HasFactory;
    protected $table = 'company_supporting_documents';
    protected $guarded = [];
    // protected $fillable = [
    //     'company_id',
    //     'company_doc_type',
    //     'document',
    //     'document_type',
    //     'document_type_name',
    // ];
}
