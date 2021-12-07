<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RecordBeneficiary;
use App\Models\Beneficiary;
use App\Repositories\PsCaseRepositoryInterface;
use Illuminate\Support\Facades\Auth;use App\Models\Gender;
use DateTime;
use Carbon\Carbon;

use App\Models\Nationality;
use App\Models\PsCase;
use App\Models\PssCase;
use App\Models\ReferralSource;
use App\Models\Referral;
use App\Models\Record;
use App\Models\Status;
use App\Models\CaseType;
use App\Models\Casee;
use App\Models\User;
use App\Models\Servicetype;
use App\Models\Reason;
use App\Models\Month;
use App\Models\Budget;
use App\Models\Team;
use App\Models\EmergencyType;

class PssCaseController extends Controller
{

    public function index(Request $request)
    {
        $pssCases = PssCase::all();
            

        $tabs = array();
        $statuses = Status::where('type', 'Psychosocial')->get();
        $tabs[0] = ['name' => 'All', 'cases' => $pssCases];

        $currentMonth = Month::where('code', date("Y-m"))->firstOrFail();
        $i = 1;
        foreach($statuses as $status){
            $cases = PssCase::whereHas('records', function($query) use($status, $currentMonth) {
                return $query->where('status_id', $status->id)->where('month_id', $currentMonth->id);
            })->get();
            $tabs[$i] = ['name' => $status->name, 'cases' => $cases];
            $i++;
        }

        $referralSources = ReferralSource::all();
        $psWorkers = User::where('job_title_id', '1')->get();
        $genders = Gender::all();
        $nationalities = Nationality::all();
        
        $teams = Team::where('department_id', '1')->get();
        $budgets = Budget::all();

        //dd($psCases);
		return view('pss_cases.index', compact('tabs','psWorkers', 'genders', 'nationalities', 'teams', 'budgets'));
    }


    public function create($id)
    {
        $directBeneficiary = Beneficiary::find($id);
        $fileId = $directBeneficiary->file->id;
        $indirectbeneficiaries = Beneficiary::where('file_id', $fileId)->where('id', '!=', $id)->get();
        //dd($indirectbeneficiaries);

        $months = Month::all();
        $referralSources = ReferralSource::all();
        $psWorkers = User::where('job_title_id', '1')->get();
        $genders = Gender::all();
        $nationalities = Nationality::all();
        $caseTypes = CaseType::all();
        $files = Casee::all();
        
        $reasons = Reason::all();

		return view('pss_cases.create', compact('directIndividual', 'indirectbeneficiaries', 'referralSources','psWorkers', 'genders', 'nationalities', 'caseTypes', 'files', 'reasons'));
    }


    public function store(Request $request)
    {
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

        //dd($monthsCodes);


        /* validate if referradate is in future (reject it - it must be today or older) */
        


        // insert Pss Case
        $pssCase = new PssCase();
        $pssCase->direct_beneficiary_id = $request->direct_beneficiary_id;
        $pssCase->current_status_id = '1';
        $pssCase->assigned_psw_id = Auth::id();
        $pssCase->save();
        
        // insert Referral
        $referral = new Referral();
        $referral->pss_case_id = $pssCase->id;
        $referral->referral_source_id = $request->referral_source_id;
        $referral->referral_date = $request->referral_date;
        $referral->referring_person_name = $request->referring_person_name;
        $referral->referring_person_email = $request->referring_person_email;
        $referral->save();

        // insert referral reasons
        $reasonsIds = $request->reasons_ids;
        foreach($reasonsIds as $reasonId)
        {
            $reason = Reason::find($reasonId);
            $referral->reasons()->attach($reason);
        }

        /////* Insert Records *///////
        //$this->insertDefaultRecords($pssCase->id, $referralMonth);
        $i = 0;
        foreach($monthsCodes as $monthCode)
        {
            $i++;
            // insert Records
            $month = Month::where('code', $monthCode)->firstOrFail();
            if($i == 1)
            {
                $record = Record::create([
                    'month_id' => $month->id,
                    'pss_case_id' => $pssCase->id,
                    'status_id' => '2',
                    'is_new' => '1',
                    'is_emergency' => '0',
                ]);
            }
            else
            {
                $record = Record::create([
                    'month_id' => $month->id,
                    'pss_case_id' => $pssCase->id,
                    'status_id' => '2',
                    'is_new' => '0',
                    'is_emergency' => '0',
                ]);
            }
            
            // Insert Direct Beneficiary In Each Record
            $directBeneficiaryId = $request->direct_beneficiary_id;
            RecordBeneficiary::create([
                'beneficiary_id' => $directBeneficiaryId,
                'record_id' => $record->id,
                'status' => '1',
            ]);


            // Insert Indirect beneficiaries In Each Record
            $indirectbeneficiariesIds = $request->indirect_beneficiaries_ids;
            if(!empty($indirectbeneficiariesIds)){
                foreach($indirectbeneficiariesIds as $indirectIndividualId)
                {
                    RecordBeneficiary::create([
                        'beneficiary_id' => $indirectIndividualId,
                        'record_id' => $record->id,
                        'status' => '0',
                    ]);
                }
            }
        }

        // return redirect()->route('psscases.show', [$pssCase->id]);
        return redirect()->route('beneficiaries.show', [$directBeneficiaryId]);
    }




    public function getMonthCaseStatus($pssCaseId, $monthId)
    {
        $record = Record::where('case_id', '=', $pssCaseId)
            ->where('month_id', '=', $monthId)->get();
        $status = $record[0]->caseStatus->name;

        return $status;
    }





    public function update(Request $request, $id)
    {

        try {
            //$validated = $request->validated();
            $psCase = PssCase::find($id);

            $psCase->referral_date = $request->referral_date;
            $psCase->direct_beneficiary_name = $request->direct_beneficiary_name;

            $psCase->save();
            return redirect()->route('pscases.index');
        }
        
        catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        PssCase::findOrFail($request->id)->delete();
        return redirect()->back();
    }





















    
    public function show($id)
    {
        // $record = Record::find(1);
        // //dd($record);
        // $beneficiaries = $record->beneficiaries;
        // $direct = $record->directBeneficiary;
        // dd($direct);
        
        $pssServices = Servicetype::where('type', 'Psychosocial')->get();
        $emergencyTypes = EmergencyType::all();

        $pssCase = PssCase::find($id);
        $referral = $pssCase->referral;
        $records = $pssCase->records;

        //$beneficiaries = $pssCase->records;

        //$julyRecord = Record::where('month_id', '7')
        //    ->where('pss_case_id', $pssCase->id)->first();
        
        //dd($julyRecord);
        //$beneficiaries = $julyRecord->beneficiaries;

        //dd($beneficiary->benefits->first()->Servicetype->name);
        //dd($beneficiary->benefits);
        
        //foreach($beneficiary->benefits as $benefit){
            //dd($benefit->Servicetype->name);
        //}

        return view('pss_cases.show', compact('pssCase', 'referral', 'records', 'pssServices', 'emergencyTypes'));

    }

    
}