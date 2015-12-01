<?php

use App\Models\Status;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('status', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->timestamps();
    });

    Status::create(['name' => 'Waiting']);
    Status::create(['name' => 'Playing']);
    Status::create(['name' => 'Finished']);
    Status::create(['name' => 'Skipped']);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('status');
  }
}
