<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $guarded =[];


    // parent tables
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }
    

    public function month()
    {
        return $this->belongsTo(Month::class, 'month_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }


    // child tables
    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function emergencies()
    {
        return $this->hasMany(Emergency::class);
    }

    public function services()
    {
        return $this->hasMany(Servicetype::class);
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'record_beneficiary', 'record_id', 'beneficiary_id');
    }

    public function recordBeneficiaries()
    {
        return $this->hasMany(RecordBeneficiary::class);
    }

    public function directRecordBeneficiaries()
    {
        return $this->hasOne(RecordBeneficiary::class)->where('status', '1');
    }

/*     public function Beneficiaries()
    {
        return $this->belongsToMany(RecordBeneficiary::class, 'records_beneficiaries', 'record_id', 'beneficiary_id');
    } */




    public function scopeNew($query)
    {
        $query->where('status_id', '1');
    }

    public function scopeMonth($query, $monthId)
    {
        $query->where('month_id', $monthId);
    }

    public function scopeMonths($query, $monthId)
    {
        $query->whereIn('month_id', $monthId);
    }

    public function scopeStatus($query, $statusId)
    {
        $query->where('status_id', $statusId);
    }


    public function scopeCurrentRecords($query, $monthId)
    {
        $query->whereIn('month_id', date("m"));
    }

}
