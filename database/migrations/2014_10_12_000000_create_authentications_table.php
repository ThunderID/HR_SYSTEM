<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthenticationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('authentications', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('menu_id')->unsigned()->index();
			$table->integer('chart_id')->unsigned()->index();
			$table->boolean('is_create');
			$table->boolean('is_read');
			$table->boolean('is_update');
			$table->boolean('is_delete');
			$table->text('settings');
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
		Schema::drop('authentications');
	}

}
