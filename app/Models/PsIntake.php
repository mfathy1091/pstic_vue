<?php

namespace App\Models;
use \Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsIntake extends Model
{
    protected $fillable = [
        'referral_source_id',
        'referral_date',
        'close_date',
        'referring_person_name',
        'referring_person_email',
        'referral_narrative_reason',
        'current_status_id',
        'current_assigned_psw_id',
        'casee_id',
        'original_direct_beneficiary_id',
    ];

        // Parent Tables
        public function beneficiaries()
        {
            return $this->belongsToMany(Beneficiary::class, 'ps_intake_beneficiaries', 'ps_intake_id', 'beneficiary_id');
        }
        
        public function current_assigned_psw()
        {
            return $this->belongsTo(User::class, 'current_assigned_psw_id');
        }
    
        public function casee()
        {
            return $this->belongsTo(Casee::class, 'casee_id');
        }
    
        public function psIntakeBeneficiaries()
        {
            return $this->hasMany(PsIntakeBeneficiary::class);
        }
    
        public function directPsIntakeBeneficiaries()
        {
            return $this->hasMany(PsIntakeBeneficiary::class)->where('is_direct', 1)
                    ->join('beneficiaries', 'ps_intake_beneficiaries.beneficiary_id', 'beneficiaries.id');
        }
    
        public function inDirectPsIntakeBeneficiaries()
        {
            return $this->hasMany(PsIntakeBeneficiary::class)->where('is_direct', 0)
            ->join('beneficiaries', 'ps_intake_beneficiaries.beneficiary_id', 'beneficiaries.id');
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
    
    
        public function userPssCases()
        {
            return $this->hasMany(PssCase::class)->where('assigned_psw_id', auth()->id());
        }
    
    
        public function reasons()
        {
            return $this->belongsToMany(Reason::class, 'ps_intake_referring_reasons', 'ps_intake_id', 'reason_id');
        }
    
    
        // pivot tables
        public function months()
        {
            return $this->belongsToMany(Month::class, 'month_referral', 'month_id', 'ps_intake_id')
            ->withPivot(['case_status']);
        }
    
        public function emergencies()
        {
            return $this->hasMany(Emergency::class)->orderBy('emergency_date', 'DESC');
        }
    
        public function activities()
        {
            return $this->hasMany(Activity::class)->orderBy('activity_date', 'DESC');
        }
    
        public function records()
        {
            return $this->hasMany(Record::class)->orderBy('month_id', 'DESC');
        }
    
        public function currentRecord()
        {
            return $this->hasOne(Record::class)->whereHas('month', function($q){
                $q->where('code', Carbon::now()->format("Y-m"));
    
                return $q;
            });
        }
    
        public function selectedRecord()
        {
            return $this->hasOne(Record::class)->where('month_id', '12');
        }
}
