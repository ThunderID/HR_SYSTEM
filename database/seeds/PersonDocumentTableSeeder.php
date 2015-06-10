<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\Person;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class PersonDocumentTableSeeder extends Seeder
{
	function run()
	{
		DB::table('persons_documents')->truncate();
		$total_person 								= Person::count();
		$total_document 							= Document::count();
		try
		{
			foreach(range(1, $total_person) as $index)
			{
				$person 							= Person::find($index);
				$doc_id 							= [];
				foreach(range(1, $total_document) as $index2)
				{
					$doc_id[]						= $index2;
				}
				$person->documents()->sync($doc_id);
				unset($doc_id);
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}