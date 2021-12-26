<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets =  Budget::all();

        $data = [
            'data' => $budgets,
        ];

        return response($data, 200);
    }
}
