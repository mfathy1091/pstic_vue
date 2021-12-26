<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiaries_emergencies', 'emergency_id', 'beneficiary_id');
    }

    public function emergencyTypes()
    {
        return $this->belongsToMany(EmergencyType::class, 'emergencies_emergency_types', 'emergency_id', 'emergency_type_id');
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

}
