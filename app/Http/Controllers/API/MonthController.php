<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\RecordBeneficiary;
use App\Models\Beneficiary;
use App\Models\Reason;
use App\Models\Casee;
Use Exception;
use \Carbon\Carbon;
class MonthController extends Controller
{

    public function index()
    {
        $months =  Month::all();
        
        $data = [
            'data' => $months,
        ];

        return response($data, 200);
    }

    public function currentMonth()
    {
        $currentMonth =  Month::where('code', Carbon::now()->format("Y-m"))->first();
        
        $data = [
            'data' => $currentMonth,
        ];

        return response($data, 200);
    }

}


