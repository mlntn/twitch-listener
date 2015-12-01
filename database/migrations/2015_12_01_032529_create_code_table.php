<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('code', function (Blueprint $table) {
      $table->increments('id');
      $table->string('user');
      $table->string('code');
      $table->integer('status_id')->default(1);
      $table->index('code');
      $table->index(['user', 'status_id'], 'user_status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('code');
  }
}
