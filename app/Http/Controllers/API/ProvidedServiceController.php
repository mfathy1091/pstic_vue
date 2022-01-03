<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProvidedService;

class ProvidedServiceController extends Controller
{
    public function index(Request $request)
    {
        $providedServices = ProvidedService::query();

        if($request->user_id != ''){
            $providedServices->where('user_id', $request->user_id);
        }

        $providedServices->with(
            'serviceType',
            'beneficiary.casee',
            'user',
            'activty.record.referral.casee'
        );

        $data = [
            'data' => $providedServices->get(),
        ];

        return response($data, 200);
    }
}
