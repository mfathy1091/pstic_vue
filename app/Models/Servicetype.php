<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicetype extends Model
{
    use HasFactory;

    protected $fillable =['name'];

    public function beneficiaries()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
