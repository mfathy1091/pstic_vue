<?php

namespace App\Providers;
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
        Emergency::created(function($emergency){
            $record = Record::findOrFail($emergency->record_id);
            $record->status_id = 1;
            $record->save();
        });

        Emergency::deleted(function($emergency){
            $record = Record::findOrFail($emergency->record_id);
            if($record->doesnthave('emergencies')){
                $record->status_id = 2;
                $record->save();
            }
        });


        Emergency::updated(function($emergency){
            $record = Record::findOrFail($emergency->record_id);
            if($record->doesnthave('emergencies')){
                $record->status_id = 2;
                $record->save();
            }
            if($record->has('emergencies')){
                $record->status_id = 1;
                $record->save();
            }
            if ($emergency->isDirty('record_id')){
                $old_record_id = $emergency->getOriginal('record_id');
                $old_record = Record::findOrFail($old_record_id);
                if($old_record->doesnthave('emergencies')){
                    $old_record->status_id = 2;
                    $old_record->save();
                }
                if($old_record->has('emergencies')){
                    $old_record->status_id = 1;
                    $old_record->save();
                }
            }
        });
    }
}
