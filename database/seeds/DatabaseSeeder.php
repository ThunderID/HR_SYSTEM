<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('FingerTableSeeder');
		$this->call('FingerPrintTableSeeder');

		$this->call('ApplicationTableSeeder');
		$this->call('MenuTableSeeder');
		$this->call('AuthGroupTableSeeder');

		$this->call('OrganisationTableSeeder');
		$this->call('BranchTableSeeder');
		$this->call('ChartTableSeeder');
		$this->call('ApiTableSeeder');

		$this->call('PersonTableSeeder');
		$this->call('RelativeTableSeeder');
		$this->call('MaritalStatusTableSeeder');

		$this->call('ContactTableSeeder');

		$this->call('CalendarTableSeeder');
		$this->call('WorkleaveTableSeeder');
		
		$this->call('WorkTableSeeder');
		$this->call('WorkAuthenticationTableSeeder');

		$this->call('DocumentTableSeeder');
		$this->call('PersonDocumentTableSeeder');
		$this->call('DocumentDetailTableSeeder');

		$this->call('PersonWidgetTableSeeder');

		$this->call('ScheduleTableSeeder');
		
		$this->call('PersonWorkleaveTableSeeder');
		$this->call('PersonScheduleTableSeeder');

		$this->call('PolicyTableSeeder');
		$this->call('LogTableSeeder');
	}

}
