<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Tenders extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tenders';
    protected $guarded = [];
    public static function boot()
    {
        parent::boot();

        // Event saat creating
        static::creating(function ($model) {
            $model->number = self::generateAutoNumber();
        });
    }
    public static function generateAutoNumber()
    {
        // Dapatkan tahun, bulan, dan hari saat ini
        $year = Carbon::now()->format('y');   // Misal: 24
        $month = Carbon::now()->format('m');  // Misal: 11
        $day = Carbon::now()->format('d');    // Misal: 13

        // Cari nomor terakhir di bulan ini
        $latestTransaction = self::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->orderBy('number', 'desc')
            ->first();

        if ($latestTransaction) {
            // Ambil bagian angka dan tambahkan 1
            $lastNumber = intval(substr($latestTransaction->number, -4)) + 1;
        } else {
            // Jika belum ada, mulai dari 1
            $lastNumber = 1;
        }

        // Format nomor menjadi 4 digit, misal: 0001, 0002, dll.
        $lastNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        // Bentuk auto number sesuai format
        return "TS/S/$year/$month/$day/$lastNumber";
    }
    public function detailProduct()
    {
        return $this->hasMany(TenderDetailProducts::class, 'tender_id','id');
    }
    public function vendorSubmission()
    {
        return $this->hasMany(TenderVendorSubmissions::class,'tender_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function eula()
    {
        return $this->belongsTo(EndUserLicenseAgreement::class,'eula_tnc_id','id');
    }
}
