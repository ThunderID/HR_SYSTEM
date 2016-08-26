<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhitelistLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('whitelist_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 255);
			$table->string('name', 255);
			$table->string('pc', 255);
			$table->datetime('on');
			$table->text('message');
			$table->string('ip', 255);
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'on']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('whitelist_logs');
	}

}
