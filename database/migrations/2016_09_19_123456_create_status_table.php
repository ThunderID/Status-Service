<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status', function (Blueprint $table) {
			$table->increments('id');
			$table->string('ref_id', 255);
			$table->integer('prev_id');
			$table->integer('next_id');
			$table->string('status', 255);
			$table->integer('position');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'ref_id', 'position']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('status');
	}
}
