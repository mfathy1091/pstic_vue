<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $guarded=[];


    public function nationality()
    {
        return $this->belongsTo('App\Models\Nationality', 'nationality_id');
    }

    public function records()
    {
        return $this->belongsToMany(Record::class, 'record_beneficiary', 'beneficiary_id', 'record_id');
    }




    
    public function gender()
    {
        return $this->belongsTo('App\Models\Gender', 'gender_id');
    }


    public function scopeWomen($query)
    {
        return $query->where('gender_id', 2);
    }



    public function scopeAgeRange($query, $from, $to)
    {
        return $query->whereBetween(['age', [$from, $to]]);
    }

    public function scopeRelatedCasebeneficiaries($query, $caseeId)
    {
        return $query->where('casee_id', $caseeId);
    }

    public function relationship()
    {
        return $this->belongsTo(Relationship::class, 'relationship_id');
    }

    public function casee()
    {
        return $this->belongsTo(Casee::class, 'casee_id');
    }

    public function referrals()
    {
        return $this->belongsToMany(Referral::class, 'referrals_beneficiaries', 'beneficiary_id', 'referral_id');
    }

/*     public function pssCases()
    {
        return $this->belongsToMany(PssCase::class, 'beneficiaries_cases', 'beneficiary_id', 'pss_casee_id');
    } */

    public function pssCases()
    {
        return $this->hasMany(PssCase::class, 'direct_beneficiary_id');
    }
}
