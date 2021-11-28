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
    Route::apiResources(['servicetypes'=> 'API\ServicetypeController']);
    Route::apiResources(['disabilities'=> 'API\DisabilityController']);


    
    // Casees
    Route::get('casees/create_or_get', 'API\CaseeController@createOrGetCasee');
    
    Route::get('casees/search', 'API\CaseeController@search');
    Route::get('casees', 'API\CaseeController@index');
    Route::post('casees', 'API\CaseeController@store');
    Route::get('casees/{id}', 'API\CaseeController@show');
    Route::put('casees/{id}', 'API\CaseeController@update');
    Route::get('casees/exists/{n}', 'API\CaseeController@exists');
    Route::get('casees/{id}/individuals', 'API\CaseeController@getIndividuals');
    
    // Referrals
    Route::apiResources(['referrals'=> 'API\ReferralController']);

    Route::get('casees/{casee}/referrals', 'API\ReferralController@getCaseeReferrals');
    Route::get('individuals/{individual}/referrals', 'API\ReferralController@getIndividualReferrals');
    Route::get('referrals/getIndividualReferrals', 'API\ReferralController@getIndividualReferrals');
    // Route::get('individuals/{individual_id}/referrals', 'API\ReferralController@getIndividualReferrals');

    // Records
    Route::get('referrals/{referral_id}/latest-record', 'API\RecordController@latestReferralRecord');
    Route::put('records/{record}', 'API\RecordController@update');
    Route::get('records/{record}', 'API\RecordController@getRecord');


    // Individuals
    Route::apiResources(['individuals'=> 'API\IndividualController']);
    Route::get('individuals/get/{caseeId}', 'API\IndividualController@getIndividuals');
    Route::get('individuals/{individual}/other_casee_individuals', 'API\IndividualController@getOtherCaseeIndividuals');
    Route::get('passport_individuals/get_individual_by_passport/{passport_number}', 'API\Individual\PassportIndividualController@getIndividualByPassword');
    Route::put('individuals/{individual}/unlink', 'API\IndividualController@unlinkCasee');

    
    // Record Beneficiaries
    // Route::apiResources(['record-beneficiaries'=> 'API\RecordBeneficiaryController']);
    Route::put('record-beneficiaries/{id}', 'API\RecordBeneficiaryController@update');
    Route::get('records/{record}/record-beneficiaries', 'API\Record\RecordBeneficiaryController@index');



    
    Route::get('abilities', 'API\AbilityController@index');
    Route::get('ability/{id}', 'API\AbilityController@show');
    Route::post('ability', 'API\AbilityController@store');
    Route::put('ability', 'API\AbilityController@update');
    Route::delete('ability/{id}', 'API\AbilityController@destroy');


    Route::apiResources(['user'=> 'API\UserController']);
    Route::apiResources(['referral_sources'=> 'API\ReferralSourceController']);

    Route::get('activities', 'API\ActivityController@index');



});

Route::group(['middleware' => ['auth.gates']], function () {
    // Route::apiResources(['referral_sources'=> 'API\ReferralSourceController']);
});

