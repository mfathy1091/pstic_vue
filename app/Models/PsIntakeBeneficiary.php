<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsIntakeBeneficiary extends Model
{
    use HasFactory;

    protected $table = 'referrals_beneficiaries';


    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function providedServices()
    {
        return $this->hasMany(ProvidedService::class, 'referral_beneficiary_id');
    }

    public function providedPss()
    {
        return $this->hasMany(ProvidedService::class, 'referral_beneficiary_id')->where('service_type_id', '1');
    }
    public function providedInfoSharing()
    {
        return $this->hasMany(ProvidedService::class, 'referral_beneficiary_id')->where('service_type_id', '2');
    }
    public function providedBasicNeed()
    {
        return $this->hasMany(ProvidedService::class, 'referral_beneficiary_id')->where('service_type_id', '3');
    }
}




