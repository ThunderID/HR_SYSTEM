<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use App\Models\Person;
use App\Models\Branch;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class ContactTableSeeder extends Seeder
{
	function run()
	{

		DB::table('contacts')->truncate();
		$faker 										= Factory::create();
		$total_persons  							= Person::count();
		$total_orgs 	 	 						= Branch::count();
		try
		{
			foreach(range(1, $total_persons) as $index)
			{
				$person 							= Person::find(ceil($index/2));

				if($index%2==1)
				{
					$is_default 					= true;
				}
				else
				{
					$is_default 					= false;
				}

				$data 								= new Contact;
				$data->fill([
					'item'							=> 'phone',
					'value'							=> $faker->phoneNumber,
					'is_default'					=> $is_default,
				]);
				$data->save();
				$data->Person()->associate($person);
				$data->save();

				$data 								= new Contact;
				$data->fill([
					'item'							=> 'bbm',
					'value'							=> $faker->phoneNumber,
					'is_default'					=> $is_default,
				]);

				$data->save();
				$data->Person()->associate($person);
				$data->save();


				$data 								= new Contact;
				$data->fill([
					'item'							=> 'line',
					'value'							=> $faker->phoneNumber,
					'is_default'					=> $is_default,
				]);

				$data->save();
				$data->Person()->associate($person);
				$data->save();

				$data 								= new Contact;
				$data->fill([
					'item'							=> 'whatsapp',
					'value'							=> $faker->phoneNumber,
					'is_default'					=> $is_default,
				]);

				$data->save();
				$data->Person()->associate($person);
				$data->save();
				
				$data 								= new Contact;
				$data->fill([
					'item'							=> 'address',
					'value'							=> $faker->address,
					'is_default'					=> $is_default,
				]);

				$data->save();
				$data->Person()->associate($person);
				$data->save();

				if($index==1)
				{
					$email 							= 'hr@thunderid.com';
				}
				else
				{
					$email 							= $faker->email;
				}
				$data 								= new Contact;
				$data->fill([
					'item'							=> 'email',
					'value'							=> $email,
					'is_default'					=> $is_default,
				]);

				$data->save();
				$data->Person()->associate($person);
				$data->save();
			} 
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	

		try
		{
			foreach(range(1, $total_orgs) as $index)
			{
				$org_branch 						= Branch::find($index);

				$data 								= new Contact;
				$data->fill([
					'item'							=> 'phone',
					'value'							=> $faker->phoneNumber,
					'is_default'					=> true,
				]);

				$data->save();
				$data->Branch()->associate($org_branch);
				$data->save();

				$data 								= new Contact;
				$data->fill([
					'item'							=> 'email',
					'value'							=> $faker->email,
					'is_default'					=> true,
				]);

				$data->save();
				$data->Branch()->associate($org_branch);
				$data->save();

				$data 								= new Contact;
				$data->fill([
					'item'							=> 'address',
					'value'							=> $faker->address,
					'is_default'					=> true,
				]);

				$data->save();
				$data->Branch()->associate($org_branch);
				$data->save();
			} 
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}
