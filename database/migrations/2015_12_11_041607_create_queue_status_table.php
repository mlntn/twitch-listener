<?php

use App\Models\QueueStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueStatusTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('queue_status', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('description')->nullable();
      $table->timestamps();
    });

    QueueStatus::create(['name'=>'None']);
    QueueStatus::create(['name'=>'In Progress']);
    QueueStatus::create(['name'=>'Completed']);
    QueueStatus::create(['name'=>'Skipped']);
    QueueStatus::create(['name'=>'Deleted']);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('queue_status');
  }
}
