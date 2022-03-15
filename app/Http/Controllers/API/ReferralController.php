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
use Illuminate\Support\Facades\DB;


class ReferralController extends Controller
{

    
    public function getMonthReferrals(Request $request)
    {
        $query = Referral::join('casees', 'referrals.casee_id', 'casees.id');
        if($request->user_id == 'current_user'){
            $query->where('current_assigned_psw_id', Auth::id());
        }
        elseif($request->user_id != ''){
            $query->where('current_assigned_psw_id', $request->user_id);
        }
        // New at Certain month
        $query->whereMonth('referral_date', '=', date('m'));

        $ReferralsQuery = clone $query;

    if($request->status_id == 1){
        $ReferralsQuery->whereHas('activities', function($q) use($request){
            $q->whereMonth('activity_date', '=', date('m'));
            return $q;
        });
    }

        
        $ReferralsQuery->with(
            //'referral.casee',
            // 'referral.beneficiaries',
            // 'referral.emergencies',
            // 'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'directReferralBeneficiaries',
            'inDirectReferralBeneficiaries',
        );
        $referralsResult = $ReferralsQuery->get();



        $data = [
            'data' => $referralsResult,
            // 'statusesCount' => $statusesCountResult,
        ];

        return response($data, 200);
    }

    public function getMonthlyReferrals(Request $request)
    {
        $query = Referral::join('casees', 'referrals.casee_id', 'casees.id');
        if($request->user_id == 'current_user'){
            $query->where('current_assigned_psw_id', Auth::id());
        }
        elseif($request->user_id != ''){
            $query->where('current_assigned_psw_id', $request->user_id);
        }
        // New at Certain month
        // $query->whereMonth('referral_date', '=', date('m'));

        $ReferralsQuery = clone $query;

        if($request->start_date !='' && $request->end_date !=''){
            $ReferralsQuery->whereHas('activities', function($q) use($request){
                $q->where('activity_date', '>=', $request->start_date);
                $q->where('activity_date', '<=', $request->end_date);
                return $q;
            });
        }

        
        $ReferralsQuery->with(
            //'referral.casee',
            // 'referral.beneficiaries',
            // 'referral.emergencies',
            // 'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'directReferralBeneficiaries',
            'inDirectReferralBeneficiaries',
        );
        $referralsResult = $ReferralsQuery->get();



        $data = [
            'data' => $referralsResult,
            // 'statusesCount' => $statusesCountResult,
        ];

        return response($data, 200);
    }

    public function index(Request $request)
    {
        $query = Referral::join('records', 'records.referral_id', 'referrals.id')
        ->join('casees', 'referrals.casee_id', 'casees.id')
        ->join('statuses', 'records.status_id', 'statuses.id')
        ->select('referrals.*', 'records.month_id', 'records.status_id');
        if($request->month_id != ''){
            $query->where('records.month_id', $request->month_id);
        }
        if($request->user_id == 'current_user'){
            $query->where('current_assigned_psw_id', Auth::id());
        }
        elseif($request->user_id != ''){
            $query->where('current_assigned_psw_id', $request->user_id);
        }
        $ReferralsQuery = clone $query;
        
        if($request->status_id != ''){
            $ReferralsQuery->where('status_id', $request->status_id);
        }
        $ReferralsQuery->with(
            //'referral.casee',
            // 'referral.beneficiaries',
            // 'referral.emergencies',
            // 'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'directReferralBeneficiaries',
            'inDirectReferralBeneficiaries',
            // 'records', 
            // 'records.month', 
            // 'records.status',
            // 'currentRecord.status' 
        );
        $referralsResult = $ReferralsQuery->get();
        
        
        $statusesCountQuery = clone $query;

        $statusesCountQuery->select('name', DB::raw('count(*) as total'))->groupBy('name');
        $statusesCountResult = $statusesCountQuery->get();

        
        $statusesCountResult = $statusesCountResult->mapWithKeys(function($item, $key){
            // if($item['status_id'] == 1){
                // return $item = $item['status_id'] *5;
                return [$item['name'] => $item['total']];
            // }    
        });



        $data = [
            'data' => $referralsResult,
            'statusesCount' => $statusesCountResult,
        ];

        return response($data, 200);
    }

    public function index2(Request $request)
    {
        $ReferralDetails = Record::query();
        $ReferralDetails->join('referrals', 'records.referral_id', 'referrals.id');



        // if($request->user_id == 'current_user'){
        //     $referrals->where('current_assigned_psw_id', Auth::id());
        // }
        // elseif($request->user_id != ''){
        //     $referrals->where('current_assigned_psw_id', $request->user_id);
        // }
        
        // if($request->casee_id != ''){
        //     $referrals->where('casee_id', $request->casee_id);
        // }

        // if($request->is_new != '' || $request->month_id != '' || $request->status_id != ''){
        //     $referrals->whereHas('records', function($q) use($request){
        //         if($request->is_new != ''){
        //             $q->where('is_new', $request->is_new);
        //         }
        //         if($request->month_id != ''){
        //             $q->where('month_id', $request->month_id);
        //         }
        //         if($request->status_id != ''){
        //             $q->where('status_id', $request->status_id);
        //         }
        //         return $q;
        //     });
        // }

        // $referrals->with(
        //     'casee',
        //     'beneficiaries',
        //     'emergencies',
        //     'activities.providedServices.serviceType',
        //     'referralSource',
        //     'current_assigned_psw',
        //     'records', 
        //     'records.month', 
        //     'records.status',
        //     'currentRecord.status' 
        // );
        
        // // selected record
        // $referrals->with(["records" => function($q) use($request){
        //     $q->where('month_id', '=', 12)->first();
        // }]);

        // $statusesCounts = array_fill(1, 3, 0);  // status 1 to 3 - default count: 0

        // foreach($referrals as $referral)
        //     $statusesCounts[]


        // $referrals->whereHas('records', function($q) use($request){
        //         $q->where('month_id', $request->month_id);
        //     return $q;
        // });

        $data = [
            'data' => $ReferralDetails->get(),
        ];

        return response($data, 200);
    }

    public function getActiveCount(Request $request)
    {
        // return Carbon::now()->format("Y-m");
        $referrals = Referral::query();

        // if($request->user_id == 'current_user'){
        //     $referrals->where('current_assigned_psw_id', Auth::id());
        // }
        // elseif($request->user_id != ''){
        //     $referrals->where('current_assigned_psw_id', $request->user_id);
        // }
        
        // if($request->casee_id != ''){
        //     $referrals->where('casee_id', $request->casee_id);
        // }

        if($request->is_new != '' || $request->month_id != '' || $request->status_id != ''){
            $referrals->whereHas('records', function($q) use($request){
                // if($request->is_new != ''){
                //     $q->where('is_new', $request->is_new);
                // }
                if($request->month_id != ''){
                    $q->where('month_id', $request->month_id);
                }
                // if($request->status_id != ''){
                //     $q->where('status_id', $request->status_id);
                // }
                $q->where('status_id', '1');
                return $q;
            });
        }

        
        // selected record
        // $referrals->with(["records" => function($q) use($request){
        //     $q->where('month_id', '=', 12)->first();
        // }]);

        // $statusesCounts = array_fill(1, 3, 0);  // status 1 to 3 - default count: 0

        // foreach($referrals as $referral)
        //     $statusesCounts[]


        // $referrals->whereHas('records', function($q) use($request){
        //         $q->where('month_id', $request->month_id);
        //     return $q;
        // });

        $data = [
            'data' => $referrals->count(),
        ];

        return response($data, 200);
    }


    public function getCaseeReferrals(Request $request, $caseeId)
    {
        $referrals =  Referral::with(
            'referralSource',
            'current_assigned_psw',
            'records', 
            'records.month', 
            'records.status')
            ->where('casee_id', $caseeId)
            ->get();

        $data = [
            'data' => $referrals,
        ];

        return response($data, 200);
    }


    public function getIndividualReferrals(Request $request)
    {
        $referrals =  Referral::with('referralSource')->where('beneficiary_id', $request->beneficiary_id)->get();
        
        $data = [
            'referrals' => $referrals,
        ];

        return response($data, 200);
    }

    public function show($id)
    {
        $referral = Referral::with(
        'beneficiaries',
        'directReferralBeneficiaries',
        'indirectReferralBeneficiaries',
        'referralBeneficiaries.beneficiary',
        'referralSource', 
        'casee',
        'casee.beneficiaries',
        'records.emergencies.user',
        'reasons', 
        'emergencies.record.month',
        'emergencies.user',
        'emergencies.emergencyTypes',
        'emergencies.beneficiary',
        'activities.providedServices.serviceType',
        'activities.serviceTypes',
        'activities.emergencyTypes',
        'activities.record.month',
        'activities.user',
        'activities.referralBeneficiary.beneficiary',
        'records', 
        'records.month', 
        'records.status', 
        'records.recordBeneficiaries',
        'records.recordBeneficiaries.individual',
        'currentRecord.status' 
        )->findOrFail($id);

        if($referral){
            return ['data' => $referral];
        }

        return ['message' => 'beneficiary does not exist'];
    }

    public function store(Request $request)
    {

        /* validate if referradate is in future (reject it - it must be today or older) */
        $this->validate($request, [
            'referral_source_id' => 'required',
            'referral_date' => 'required',
            'referring_person_name' => 'required',
            'referring_person_email' => 'required|email',
            'referral_narrative_reason' => 'required',
            'current_status_id' => '',
            'current_assigned_psw_id' => '',
        ]);

        // Create Referral
        $referral = Referral::create([
            'referral_source_id' => $request->referral_source_id,
            'referral_date' => $request->referral_date,
            'referring_person_name' => $request->referring_person_name,
            'referring_person_email' => $request->referring_person_email,
            'referral_narrative_reason' => $request->referral_narrative_reason,
            'current_status_id' => $request->current_status_id,
            'current_assigned_psw_id' => Auth::id(),
            'casee_id' => $request->casee_id,
        ]);

        

        // Referral Month
        $referralDate = $request->referral_date;
        $ConvertedReferralDate = strtotime($referralDate);
        $referralMonth = date("Y-m", $ConvertedReferralDate);
        $currentMonth = date("Y-m");

        // Get Months List
        $period = \Carbon\CarbonPeriod::create($referralMonth, '1 month', $currentMonth);

        $i = 0;
        foreach ($period as $dt)
        {
            $monthsCodes[$i] = $dt->format("Y-m");
            $i++;
        }

        // dd($monthsCodes);


    

        // // insert referral reasons
        // $reasonsIds = $request->reasons_ids;
        // foreach($reasonsIds as $reasonId)
        // {
        //     $reason = Reason::find($reasonId);
        //     $referral->reasons()->attach($reason);
        // }

        // then sync
        $reasonsIds = collect($request->input('referral_reasons'))->pluck('id');
        $referral->reasons()->sync($reasonsIds);

        /////* Insert Records *///////
        //$this->insertDefaultRecords($pssCase->id, $referralMonth);

        


        $directBeneficiariesIds = collect($request->input('direct_referral_beneficiaries'))->pluck('id')->toArray();

        $casee = Casee::find($request->casee_id);
        $ActiveBeneficiariesIds = $casee->activeBeneficiaries->pluck('id');

        $ActiveBeneficiariesIds = $ActiveBeneficiariesIds->mapWithKeys(function($item, $key) use($directBeneficiariesIds){
            if(in_array($item, $directBeneficiariesIds) ){
                return [$item => ['is_direct' => 1]];
            }else{
                return [$item => ['is_direct' => 0]];
            }    
        });

        $referral->beneficiaries()->sync($ActiveBeneficiariesIds);




        $i = 0;
        foreach($monthsCodes as $monthCode)
        {
            $i++;
            // insert Records
            $month = Month::where('code', $monthCode)->firstOrFail();
            if($i == 1){
                $is_new = 1;
            }else{
                $is_new = 0;
            }
            $record = Record::create([
                'month_id' => $month->id,
                'elapsed_months_since_intake' => $i-1,
                'referral_id' => $referral->id,
                'status_id' => '2',
                'is_new' => $is_new,
            ]);
            
            // $record->beneficiaries()->sync($beneficiariesIds->toArray());
    
        }

        $data = [
            'referral' => $referral,
            'message' => 'created successfully'
        ];

        return response($data, 201);

    }
    

    public function closeReferral(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);
        $referral->close_date = date("Y-m-d");
        $referral->save();

        // if it exists
        if($referral){
            $record = $referral->records->first();

            $record->status_id = 3;
            $record->save();
            return $record;
        }
    }

    public function update(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);

        // if it exists
        if($referral){

            $this->validate($request, [
                'referral_source_id' => 'required',
                'referral_date' => 'required',
                'referring_person_name' => 'required',
                'referring_person_email' => 'required|email',
                'referral_narrative_reason' => 'required',
                'current_status_id' => '',
                'current_assigned_psw_id' => '',
            ]);
            
            // update first
            $referral->update([
                'referral_source_id' => $request->referral_source_id,
                'referral_date' => $request->referral_date,
                'referring_person_name' => $request->referring_person_name,
                'referring_person_email' => $request->referring_person_email,
                'referral_narrative_reason' => $request->referral_narrative_reason,
                'current_status_id' => $request->current_status_id,
                'current_assigned_psw_id' => Auth::id(),
                'casee_id' => $request->casee_id,
            ]);

            // then sync
            $reasonsIds = collect($request->input('referral_reasons'))->pluck('id');
            $referral->reasons()->sync($reasonsIds);
            
            // Attach Beneficiaries to Referral
            $directBeneficiariesIds = collect($request->input('direct_referral_beneficiaries'))->pluck('id')->toArray();

            $casee = Casee::find($request->casee_id);
            $ActiveBeneficiariesIds = $casee->activeBeneficiaries->pluck('id');
    
            $ActiveBeneficiariesIds = $ActiveBeneficiariesIds->mapWithKeys(function($item, $key) use($directBeneficiariesIds){
                if(in_array($item, $directBeneficiariesIds) ){
                    return [$item => ['is_direct' => 1]];
                }else{
                    return [$item => ['is_direct' => 0]];
                }    
            });
    
            $referral->beneficiaries()->sync($ActiveBeneficiariesIds);
            
            return ['message' => 'Referral updated'];
        }

    }


    public function destroy($id)
    {
        $referral = Referral::findOrFail($id);

        // if it exists
        if($referral){
            // detach first
            $referral->permissions()->detach();
            // then delete
            $referral->delete();
        }

        return ['message' => 'Referral deleted'];
    }

}


