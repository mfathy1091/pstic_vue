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
}
