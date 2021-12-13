<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'original_direct_beneficiary_id',
        'referral_source_id',
        'referral_date',
        'referring_person_name',
        'referring_person_email',
        'referral_narrative_reason',
        'current_status_id',
        'current_assigned_psw_id',
        'casee_id'
    ];

    // Parent Tables
    public function casee()
    {
        return $this->belongsTo(Casee::class, 'casee_id');
    }

    public function directIndividual()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
    
    public function originalDirectIndividual()
    {
        return $this->belongsTo(Beneficiary::class, 'original_direct_beneficiary_id');
    }
    
    public function referralSource()
    {
        return $this->belongsTo(ReferralSource::class, 'referral_source_id');
    }



    // Child tables
    public function pssCase()
    {
        return $this->belongsTo(PssCase::class, 'pss_case_id');
    }

    public function activeReferrals()
    {
        return $this->hasOne(PssCase::class);
    }

    public function userPssCases()
    {
        return $this->hasMany(PssCase::class)->where('assigned_psw_id', auth()->id());
    }


    public function reasons()
    {
        return $this->belongsToMany(Reason::class, 'referral_reasons', 'referral_id', 'reason_id');
    }


    // pivot tables
    public function months()
    {
        return $this->belongsToMany(Month::class, 'month_referral', 'month_id', 'referral_id')
        ->withPivot(['case_status']);
    }

    public function sections()
    {
        return $this->belongstoMany(Section::class, 'referral_sections', 'referral_id', 'section_id')
        ->withPivot(['assigned_worker_id', 'direct_beneficiary_id', 'current_status_id'])
        ->using(ReferralSection::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class)->orderBy('month_id', 'DESC');
    }

    public function currentRecord()
    {
        return $this->hasMany(Record::class)->orderBy('month_id', 'DESC');
    }

    
}




