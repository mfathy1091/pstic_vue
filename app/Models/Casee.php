<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casee extends Model
{
    protected $fillable = ['file_number', 'is_family', 'created_user_id'];


    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function activeBeneficiaries()
    {
        return $this->hasMany(Beneficiary::class)->where('is_active', '1');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function housingReferrals()
    {
        return $this->hasMany(HousingReferral::class);
    }

    public function pssCases()
    {
        return $this->hasMany(PssCase::class);
    } 
}
