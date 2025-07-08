<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
        // return asset('images/'.\Auth::user()->avatar);
    }
    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }
    public function adminlte_profile_url()
    {
        // return 'profile/username';
        return 'profile';
    }
    public function office()
    {
        return $this->hasOne(MasterOffice::class, 'id', 'office_id');
    }
    public function division()
    {
        return $this->belongsTo(MasterDivision::class, 'division_id');
    }
    public function dept()
    {
        return $this->hasOne(MasterDepartment::class, 'id', 'department_id');
    }
    public function jobTitle()
    {
        return $this->belongsTo(MasterJobTitles::class, 'job_title_id');
    }
    public function level()
    {
        return $this->hasOneThrough(
            MasterLevelJobs::class,
            MasterJobTitles::class,
            'id',          // Foreign key di MasterJobTitles
            'id',          // Foreign key di MasterLevelJobs
            'job_title_id',// Local key di Users
            'level_id'     // Local key di MasterJobTitles
        );
    }
    public function parent()
    {
        return $this->hasMany(User::class, 'parent_user_id');
    }
    public function companyInformation()
    {
        return $this->hasOne(CompanyInformation::class);
    }

}
