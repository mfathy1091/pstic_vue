<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Emergency;
use App\Models\Record;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Activity::created(function($activity){

            $records = $activity->referral->records;
            
            foreach($records as $record){
                if($record->activities->count()){
                    // dd($record->activities);
                    $record->status_id = 1;
                    $record->save();
                }
                else{
                    // dd("doesn't haave");
                    $record->status_id = 2;
                    $record->save();
                }
            }
        });

        Activity::deleted(function($activity){

            $records = $activity->referral->records;
            
            foreach($records as $record){
                if($record->activities->count()){
                    // dd($record->activities);
                    $record->status_id = 1;
                    $record->save();
                }
                else{
                    // dd("doesn't haave");
                    $record->status_id = 2;
                    $record->save();
                }
            }
        });


        Activity::updated(function($activity){

            $records = $activity->referral->records;
            
            foreach($records as $record){
                if($record->activities->count()){
                    // dd($record->activities);
                    $record->status_id = 1;
                    $record->save();
                }
                else{
                    // dd("doesn't haave");
                    $record->status_id = 2;
                    $record->save();
                }
            }

            // if ($emergency->isDirty('record_id')){
            //     $old_record_id = $emergency->getOriginal('record_id');
            //     $old_record = Record::findOrFail($old_record_id);
            //     if($old_record->doesnthave('emergencies')){
            //         $old_record->status_id = 2;
            //         $old_record->save();
            //     }
            //     if($old_record->has('emergencies')){
            //         $old_record->status_id = 1;
            //         $old_record->save();
            //     }
            // }
        });
    }
}
