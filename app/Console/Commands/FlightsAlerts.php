<?php

namespace App\Console\Commands;

use App\Models\Alerts;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlightsAlerts extends Command implements SelfHandling, ShouldQueue
{

	protected $signature = 'flights:alerts {flight_id}';

	protected $description = 'Send flights alerts';


	public function __construct()
	{
		parent::__construct();
	}


	public function handle()
	{

		$flight_id = $this->argument('flight_id');

		$alerts = Alerts::where('active', '=', 1)->get();
		
		if(!$alerts->count())
			return FALSE;
		
		foreach ( $alerts->toArray() as $alert )
		{
			$alert['flight_id'] = $flight_id;
			\Queue::push(new \App\Jobs\FlightsAlerts($alert));
		}


	}

}
