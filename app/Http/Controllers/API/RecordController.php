<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Record;
use App\Models\RecordBeneficiary;

class RecordController extends Controller
{

    

    public function latestReferralRecord($referralId)
    {
        $record =  Record::where('referral_id', $referralId)->first();
        return response(['data' => $record], 200);
        
    }

    public function index(Request $request)
    {
        $records = Record::query();

        if($request->user_id == 'current_user'){
            $referrals->where('current_assigned_psw_id', Auth::id());
        }
        elseif($request->user_id != ''){
            $referrals->where('current_assigned_psw_id', $request->user_id);
        }
        
        if($request->casee_id != ''){
            $referrals->where('casee_id', $request->casee_id);
        }

        if($request->is_new != '' || $request->month_id != '' || $request->status_id != ''){
            $referrals->whereHas('records', function($q) use($request){
                if($request->is_new != ''){
                    $q->where('is_new', $request->is_new);
                }
                if($request->month_id != ''){
                    $q->where('month_id', $request->month_id);
                }
                if($request->status_id != ''){
                    $q->where('status_id', $request->status_id);
                }
                return $q;
            });
        }


        $referrals->with(
            'casee',
            'beneficiaries',
            'emergencies',
            'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'records', 
            'records.month', 
            'records.status',
            'currentRecord.status' );

        $data = [
            'data' => $referrals->get(),
        ];

        return response($data, 200);
    }


    public function show($id)
    {
        $record = Record::with(
            'month',
            'status',
            'emergencies.user',
            'emergencies.emergencyType',
            'recordBeneficiaries',
            'recordBeneficiaries.individual',
            //'records.recordBeneficiaries.services'
            )->findOrFail($id);

        if($record){
            return response(['data' => $record], 200);
        }
        
    }

    public function update(Request $request, $id)
    {
        $record = Record::findOrFail($id);

        if($record){
            $this->validate($request, [
                'month_id' => 'required|string|max:255',
                'referral_id' => 'required|string|max:255',
                'status_id' => 'required|string|max:255',
                'is_new' => 'required|string|max:255',
            ]);

            $result = [];
            if($record){
                $record->update([
                    'month_id' => $request->month_id,
                    'referral_id' => $request->referral_id,
                    'status_id' => $request->status_id,
                    'is_new' => $request->is_new,
                ]);
                $recordBeneficiaries = $request->recordBeneficiaries;


                foreach($recordBeneficiaries as $i => $object) {
                    $key = $object['beneficiary_id'];
                    
                    $result[$key] = ['status' => $object['status']];
                }
                $record->beneficiaries()->sync($result);
                
                // $record->beneficiaries()->sync([
                //     1 => ['status' => '1'],
                //     2 => ['status' => '0']
                // ]);
            }

            
            $data = [
                'record' => $record,
                'recordBeneficiaries' => $request->recordBeneficiaries,
                'result' => $result,
                'result2' => [
                    1 => ['status' => '1'],
                    2 => ['status' => '0']
                ],
            ];
    
            return response($data, 200);
        }

    }

}
