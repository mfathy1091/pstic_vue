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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    
    return $request->user();
});

Route::middleware('auth:api')->group( function () {
    Route::apiResources(['user'=> 'API\UserController']);
    Route::apiResources(['role'=> 'API\RoleController']);
    Route::apiResources(['permission'=> 'API\PermissionController']);
    Route::apiResources(['nationalities'=> 'API\NationalityController']);
    Route::apiResources(['relationships'=> 'API\RelationshipController']);
    Route::apiResources(['referral-sources'=> 'API\ReferralSourceController']);
    
    // Files
    Route::get('files/exists/{n}', 'API\FileController@exists');
    Route::get('files/get/{n}', 'API\FileController@get');
    Route::get('files/{id}/individuals', 'API\FileController@getIndividuals');


    // Individuals
    Route::apiResources(['individuals'=> 'API\IndividualController']);
    Route::get('individuals/get/{fileId}', 'API\IndividualController@getIndividuals');
    Route::get('individuals/{individual}/referrals', 'API\Individual\ReferralController@index');


    // Referrals
    Route::apiResources(['referrals'=> 'API\ReferralController']);
    Route::get('referrals/getIndividualReferrals', 'API\ReferralController@getIndividualReferrals');
    // Route::get('individuals/{individual_id}/referrals', 'API\ReferralController@getIndividualReferrals');
    
    Route::get('abilities', 'AbilityController@index');
    Route::get('ability/{id}', 'AbilityController@show');
    Route::post('ability', 'AbilityController@store');
    Route::put('ability', 'AbilityController@update');
    Route::delete('ability/{id}', 'AbilityController@destroy');
});





