<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpQueuesMorphsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('queues_morphs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('queue_id')->unsigned()->index();
			$table->integer('queue_morph_id')->unsigned()->index();
			$table->string('queue_morph_type', 255);
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('queues_morphs');
	}

}
