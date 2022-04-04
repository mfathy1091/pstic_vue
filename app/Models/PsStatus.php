<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsStatus extends Model
{
    protected $fillable =['name'];


    public function psCases()
    {
        return $this->hasMany(PsCase::class);
    }
}
