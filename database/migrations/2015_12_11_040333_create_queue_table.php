<?php

use App\Models\QueueStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('queue', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id');
      $table->string('user');
      $table->string('item');
      $table->string('name')->nullable();
      $table->text('description')->nullable();
      $table->integer('queue_status_id')->default(QueueStatus::NONE);
      $table->index(['user_id', 'user', 'item'], 'channel_user_item');
      $table->index(['user_id', 'queue_status_id'], 'channel_status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('queue');
  }
}
