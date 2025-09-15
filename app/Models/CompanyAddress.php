<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
/**
 * @property int $id
 * @property int $company_id
 * @property string $address
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $zip_code
 * @property string $telephone
 * @property string|null $fax
 */
class CompanyAddress extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $table = 'company_addresses';
    protected $fillable = [
        'company_id',
        'address',
        'country',
        'province',
        'city',
        'zip_code',
        'telephone',
        'fax',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class, 'company_id', 'id');
    }
}
