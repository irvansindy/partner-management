<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(CompanyAttachment::class, 'company_id', 'id');
    }

    public function salesSurvey()
    {
        return $this->hasOne(SalesSurvey::class, 'company_id');
    }

    public function productCustomers()
    {
        return $this->hasMany(ProductCustomer::class, 'company_id');
    }

    public function formLink()
    {
        return $this->belongsTo(FormLink::class, 'form_link_id');
    }

    public function liablePeople()
    {
        return $this->hasMany(CompanyLiablePerson::class, 'company_id');
    }

    /**
     * ✅ NEW: Relation to approval process
     */
    public function approvalProcess()
    {
        return $this->hasOne(ApprovalProcess::class, 'company_information_id', 'id');
    }

    /**
     * ✅ NEW: Check if company has approval process
     */
    public function hasApproval(): bool
    {
        return $this->approvalProcess()->exists();
    }

    /**
     * ✅ NEW: Get approval status
     */
    public function getApprovalStatus(): ?string
    {
        if (!$this->hasApproval()) {
            return null;
        }

        return match($this->approvalProcess->status) {
            0 => 'Menunggu',
            1 => 'Dalam Proses',
            2 => 'Disetujui',
            3 => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * ✅ NEW: Check if approved
     */
    public function isApproved(): bool
    {
        return $this->hasApproval() && $this->approvalProcess->status === 2;
    }

    /**
     * ✅ NEW: Check if rejected
     */
    public function isRejected(): bool
    {
        return $this->hasApproval() && $this->approvalProcess->status === 3;
    }

    /**
     * ✅ NEW: Check if in progress
     */
    public function isApprovalInProgress(): bool
    {
        return $this->hasApproval() && in_array($this->approvalProcess->status, [0, 1]);
    }
}
