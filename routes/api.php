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
    Route::apiResources(['services'=> 'API\ServiceController']);
    Route::apiResources(['disabilities'=> 'API\DisabilityController']);


    
    // Files
    Route::get('files/create_or_get', 'API\FileController@createOrGetFile');
    Route::post('files', 'API\FileController@store');
    Route::get('files/{id}', 'API\FileController@show');
    Route::put('files/{id}', 'API\FileController@update');
    Route::get('files/exists/{n}', 'API\FileController@exists');
    Route::get('files/{id}/individuals', 'API\FileController@getIndividuals');


    // Individuals
    Route::apiResources(['individuals'=> 'API\IndividualController']);
    Route::get('individuals/get/{fileId}', 'API\IndividualController@getIndividuals');
    Route::get('individuals/{individual}/other_file_individuals', 'API\IndividualController@getOtherFileIndividuals');
    Route::get('individuals/{individual}/referrals', 'API\Individual\ReferralController@index');
    Route::get('passport_individuals/get_individual_by_passport/{passport_number}', 'API\Individual\PassportIndividualController@getIndividualByPassword');
    Route::put('individuals/{individual}/unlink', 'API\IndividualController@unlinkFile');

    
    // Beneficiaries
    Route::apiResources(['beneficiaries'=> 'API\BeneficiaryController']);
    Route::get('records/{record}/beneficiaries', 'API\Record\BeneficiaryController@index');

    // Referrals
    Route::apiResources(['referrals'=> 'API\ReferralController']);
    Route::get('referrals/getIndividualReferrals', 'API\ReferralController@getIndividualReferrals');
    // Route::get('individuals/{individual_id}/referrals', 'API\ReferralController@getIndividualReferrals');

    // Records
    Route::get('referrals/{referral_id}/latest-record', 'API\RecordController@latestReferralRecord');
    
    
    Route::get('abilities', 'API\AbilityController@index');
    Route::get('ability/{id}', 'API\AbilityController@show');
    Route::post('ability', 'API\AbilityController@store');
    Route::put('ability', 'API\AbilityController@update');
    Route::delete('ability/{id}', 'API\AbilityController@destroy');


    Route::apiResources(['user'=> 'API\UserController']);
    Route::apiResources(['referral_sources'=> 'API\ReferralSourceController']);




});

Route::group(['middleware' => ['auth.gates']], function () {
    // Route::apiResources(['referral_sources'=> 'API\ReferralSourceController']);
});

