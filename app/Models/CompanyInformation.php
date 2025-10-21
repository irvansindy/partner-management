<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $group_name
 * @property string $type
 * @property string $established_year
 * @property int $total_employee
 * @property string $liable_person_and_position
 * @property string $owner_name
 * @property string $board_of_directors
 * @property string $major_shareholders
 * @property string $business_classification
 * @property string|null $business_classification_detail
 * @property string|null $other_business
 * @property string $website_address
 * @property string $system_management
 * @property string $contact_person
 * @property string $communication_language
 * @property string $email_address
 * @property string|null $remark
 * @property string|null $signature
 * @property string|null $stamp
 * @property string|null $supplier_number
 * @property string $status
 * @property int $location_id
 * @property int $department_id
 * @property int $blacklist
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyAddress[] $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyBank[] $bank
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyTax[] $tax
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanySupportingDocument[] $attachment
 */

class CompanyInformation extends Model
{
    use HasFactory;

    protected $table = 'company_informations';
    protected $guarded = [];
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function contact()
    {
        return $this->hasMany(CompanyContact::class,'company_informations_id','id');
    }
    public function address()
    {
        return $this->hasMany(CompanyAddress::class, 'company_id', 'id');
    }
    public function AddressBaseOnMap()
    {
        return $this->hasOne(CompanyAddress::class, 'company_id', 'id')->oldest();
    }

    public function bank()
    {
        return $this->hasMany(CompanyBank::class, 'company_id', 'id');
    }
    public function tax()
    {
        return $this->hasMany(CompanyTax::class, 'company_id', 'id');
    }
    public function attachment()
    {
        return $this->hasMany(CompanySupportingDocument::class, 'company_id', 'id');
    }
}
