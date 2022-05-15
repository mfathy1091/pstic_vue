<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsIntakeStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'ps_intake_id',
        'status_id',
        'month',
    ];

    public $timestamps = FALSE;
}
