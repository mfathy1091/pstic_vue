<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingReferral extends Model
{
    protected $fillable = [
        'casee_id',
        'referral_source_id',
        'referral_date',
        'referring_person_name',
        'referring_person_email',
        'referral_narrative_reason',
        'grant_status_id',
        'grant_amount', 
        'assigned_housing_advocate_id',
    ];

    
    // Parent Tables
    public function assignedHousingAdvocate()
    {
        return $this->belongsTo(User::class, 'assigned_housing_advocate_id');
    }

    public function casee()
    {
        return $this->belongsTo(Casee::class, 'casee_id');
    }
    
    public function referralSource()
    {
        return $this->belongsTo(ReferralSource::class, 'referral_source_id');
    }

    public function grantStatus()
    {
        return $this->belongsTo(Status::class, 'grant_status_id');
    }

    // Child tables

}
