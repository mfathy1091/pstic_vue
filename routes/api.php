<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', 'API\AuthController@logout');
    Route::prefix('/user')->group(function () {
        Route::get('/current', 'API\UserController@currentUser');
    }) ;
    
    Route::apiResources(['roles'=> 'API\RoleController']);
    Route::apiResources(['permission'=> 'API\PermissionController']);
    Route::apiResources(['nationalities'=> 'API\NationalityController']);
    Route::apiResources(['relationships'=> 'API\RelationshipController']);
    Route::apiResources(['referral_reasons'=> 'API\ReasonController']);
    Route::apiResources(['service-types'=> 'API\ServiceTypeController']);
    Route::apiResources(['disabilities'=> 'API\DisabilityController']);
    Route::apiResources(['budgets'=> 'API\BudgetController']);


    // statuses
    Route::get('housing-grant-statuses', 'API\StatusController@getHousingGrantStatuses');
    Route::get('beneficiary-statuses', 'API\StatusController@getBeneficiaryStatuses');

    // Provided Services
    Route::get('provided-services', 'API\ProvidedServiceController@index');

    
    // Casees
    Route::get('casees/{id}/referrals', 'API\CaseeController@getReferrals')->where('id', '[0-9]+');
    Route::get('casees/create_or_get', 'API\CaseeController@createOrGetCasee');
    Route::get('casees/search', 'API\CaseeController@search');
    Route::get('casees', 'API\CaseeController@index');
    Route::post('casees', 'API\CaseeController@store');
    Route::get('casees/{id}', 'API\CaseeController@show')->where('id', '[0-9]+');
    Route::put('casees/{id}', 'API\CaseeController@update')->where('id', '[0-9]+');
    Route::get('casees/exists/{n}', 'API\CaseeController@exists');
    
    // PSS Referrals
    Route::get('referrals/monthly', 'API\ReferralController@getMonthlyReferrals');
    Route::get('beneficiaries/monthly_referral_beneficiaries', 'API\BeneficiaryController@getMonthlyReferralBeneficiaries');

    Route::get('referrals/currentMonth', 'API\ReferralController@getCurrentMonthReferrals');
    Route::get('referrals/activeCount', 'API\ReferralController@getActiveCount');
    Route::put('referrals/{id}/close', 'API\ReferralController@closeReferral');
    Route::get('beneficiaries/{individual}/referrals', 'API\ReferralController@getIndividualReferrals');
    Route::get('referrals/getIndividualReferrals', 'API\ReferralController@getIndividualReferrals');
    Route::get('referrals', 'API\ReferralController@index')->where('id', '[0-9]+');
    Route::get('referrals/{id}', 'API\ReferralController@show')->where('id', '[0-9]+');

    // Housing Referrals
    Route::get('casees/{casee}/housing-referrals', 'API\HousingReferralController@getCaseeHousingReferrals');
    Route::get('current-housing-advocate/housing-referrals', 'API\HousingReferralController@getCurrentHousingAdvocateHousingReferrals');
    Route::post('housing-referrals/', 'API\HousingReferralController@store');
    Route::put('housing-referrals/{id}', 'API\HousingReferralController@update');
    Route::get('casees/{casee}/housing-referrals', 'API\HousingReferralController@getCaseeHousingReferrals');

    // Records
    Route::get('referrals/{referral_id}/latest-record', 'API\RecordController@latestReferralRecord');
    Route::put('records/{record}', 'API\RecordController@update');
    Route::get('records/search', 'API\RecordController@search');
    Route::get('records/{record}', 'API\RecordController@show');
   


    // beneficiaries
    Route::get('beneficiaries/search/', 'API\BeneficiaryController@search');
    Route::put('beneficiaries/{individual}/deactivate', 'API\BeneficiaryController@deactivateBeneficiary');
    Route::put('beneficiaries/{individual}/activate', 'API\BeneficiaryController@activateBeneficiary');
    Route::apiResources(['beneficiaries'=> 'API\BeneficiaryController']);
    Route::get('beneficiaries/get/{caseeId}', 'API\BeneficiaryController@getbeneficiaries');
    Route::get('beneficiaries/{individual}/other_casee_beneficiaries', 'API\BeneficiaryController@getOtherCaseebeneficiaries');
    Route::get('passport_beneficiaries/get_individual_by_passport/{passport_number}', 'API\Individual\PassportBeneficiaryController@getIndividualByPassword');
    Route::get('casees/{casee}/beneficiaries', 'API\BeneficiaryController@getCaseeBeneficiaries');
    
    // Referral Beneficiaries
    Route::get('referral-beneficiaries/', 'API\ReferralBeneficiaryController@index');
    Route::get('referral-beneficiaries/search', 'API\ReferralBeneficiaryController@search');
    Route::get('referral-beneficiaries/stats/', 'API\ReferralBeneficiaryController@getStats');

    // Record Beneficiaries
    // Route::apiResources(['record-beneficiaries'=> 'API\RecordBeneficiaryController']);
    Route::put('record-beneficiaries/{id}', 'API\RecordBeneficiaryController@update');
    Route::get('records/{record}/record-beneficiaries', 'API\Record\RecordBeneficiaryController@index');

    
    Route::get('abilities', 'API\AbilityController@index');
    Route::get('ability/{id}', 'API\AbilityController@show');
    Route::post('ability', 'API\AbilityController@store');
    Route::put('ability', 'API\AbilityController@update');
    Route::delete('ability/{id}', 'API\AbilityController@destroy');


    Route::apiResources(['users'=> 'API\UserController']);
    Route::apiResources(['referral_sources'=> 'API\ReferralSourceController']);

    Route::get('activities', 'API\ActivityController@index');
    Route::post('activities', 'API\ActivityController@store');
    Route::put('activities/{id}', 'API\ActivityController@update');
    Route::delete('activities/{id}', 'API\ActivityController@destroy');

    // Emergencies
    Route::get('emergencies', 'API\EmergencyController@index');
    Route::post('emergencies', 'API\EmergencyController@store');
    Route::put('emergencies/{id}', 'API\EmergencyController@update');
    Route::delete('emergencies/{id}', 'API\EmergencyController@destroy');

    // Emergency types
    Route::get('emergency-types', 'API\EmergencyTypeController@index');


    // Test
    Route::get('test', 'API\TestController@index');

    Route::get('months', 'API\MonthController@index');
    Route::get('months/current', 'API\MonthController@currentMonth');

});

Route::group(['middleware' => ['auth.gates']], function () {
    // Route::apiResources(['referral_sources'=> 'API\ReferralSourceController']);
});

