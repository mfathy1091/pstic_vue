<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordBeneficiary extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'record_beneficiary';

    public function individual()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }





    public function followUps()
    {
        return $this->hasMany(followUp::class);
    }

    public function disabilities(){
        return $this->belongsToMany(Disability::class);
    }
    
    public function servicetyps()
    {
        return $this->belongsToMany(Servicetype::class);
    }

    // public function pssCase()
    // {
    //     return $this->belongsTo(PssCase::class, 'pss_case_id');
    // }



    // public function month()
    // {
    //     return $this->belongsTo(Month::class, 'month_id');
    // }

    // public function Servicetypes()
    // {
    //     return $this->belongsToMany(Servicetype::class, 'services_beneficiaries', 'beneficiary_id', 'service_id');

    // }

    // public function records()
    // {
    //     return $this->belongsToMany(Record::class, 'records_beneficiaries', 'beneficiary_id', 'record_id');
    // }

}
