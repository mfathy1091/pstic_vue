<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    public function employees(){
        return $this->hasmany(Employee::class);
    }
}
