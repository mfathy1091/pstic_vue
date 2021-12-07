<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PssCase extends Model
{

    protected $guarded =[];

    // parent tables

    public function casee()
    {
        return $this->belongsTo(Casee::class, 'casee_id');
    }

    public function referral()
    {
        return $this->hasOne(Referral::class);
    }

    public function month()
    {
        return $this->hasOne(Month::class);
    }

    public function currentStatus()
    {
        return $this->belongsTo(Status::class, 'current_status_id');
    }

    public function assignedPsw()
    {
        return $this->belongsTo(User::class, 'assigned_psw_id');
    }

    public function directIndividual()
    {
        return $this->belongsTo(Beneficiary::class, 'direct_beneficiary_id');
    }



    // child tables
/*     public function directBeneficiary()
    {
        return $this->hasOne(Beneficiary::class)->direct();;
    }
 */
    public function beneficiariesIndirect()
    {
        return $this->hasMany(Beneficiary::class)->indirect();
        // or this way:
        // return $this->posts()->published();
    }


    public function visits()
    {
        return $this->hasMany(Visit::class);
    }




    public function currentStatus2()
    {
        $status = Record::with('caseStatus', 'month')
        ->where('month_id', date('n'))
        ->get()
        ->map(function ($record){
            return[
                'currentStatus' => $record->caseStatus->name,
                'currentMonth' => $record->month->name,
            ];
        });

        return $status[0]['currentStatus'];
    }


    public function user_referrals()
    {
        return $this->hasMany(Referral::class)->where('user_id', auth()->id());
    }



    public function records()
    {
        return $this->hasMany(Record::class)->orderBy('month_id', 'DESC');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class)->orderBy('status', 'DESC');
    }

    public function currentRecord()
    {
        return $this->hasone(Record::class)->whereHas('month', function($q){
            return $q->where('code', date("Y-m"));
        });
        //->where('month_id', date("Y-m"))
    }

    public function recordsByMonth($month)
    {
        return $this->hasMany(Record::class)
            ->where('month', $month);
    }



    public function recordsByMonthlyStatus($month, $status)
    {
        return $this->hasMany(Record::class)
            ->where('month', $month)
            ->where('status_id', $status);
    }















    public function scopeUserPssCases($query)
    {
        $worker = Auth::user()->employee;

        $pssCases = PssCase::where('assigned_psw_id', $worker->id)
            ->get();
    }









}