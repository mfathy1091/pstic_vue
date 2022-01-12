<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function referralBeneficiary()
    {
        return $this->belongsTo(ReferralBeneficiary::class, 'referral_beneficiary_id');
    }

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }
    
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }

    public function casee()
    {
        return $this->belongsTo(Casee::class, 'casee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function providedServices()
    {
        return $this->hasMany(ProvidedService::class);
    }

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'provided_services', 'activity_id', 'service_type_id');
    }

}
