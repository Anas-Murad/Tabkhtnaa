<?php

namespace App\Jobs;

use App\Models\Country;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPointsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {



        $countries= Country::with([
            'configuration'=>function($q){
                $q->whereIn('classification' , ['points' ,'distinction']) ;
            }
        ])->whereHas('users')
        ->with('users')
            ->select('id' , 'name')
        ->get();


\Log::info('users' , ['users'=>$countries]);



            return time();
    }
}
