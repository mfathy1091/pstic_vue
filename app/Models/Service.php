<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $guarded=[];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }

    public function servicetype()
    {
        return $this->belongsTo(Servicetype::class, 'servicetype_id');
    }
}
