<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryService extends Model
{
    use HasFactory;

    // protected $fillable = [];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function servicetype()
    {
        return $this->belongsTo(Servicetype::class, 'service_id');
    }

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }
}
