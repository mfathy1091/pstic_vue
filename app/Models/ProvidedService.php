<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidedService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activty()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}

