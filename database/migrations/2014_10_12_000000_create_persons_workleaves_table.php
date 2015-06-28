<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsWorkleavesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('persons_workleaves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->index();
			$table->integer('workleave_id')->unsigned()->index();
			$table->integer('created_by')->unsigned()->index();
			$table->date('start');
			$table->date('end');
			$table->boolean('is_default');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'person_id', 'start','is_default']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('persons_workleaves');
	}

}
