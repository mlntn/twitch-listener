<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserKeywordActionTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('user_keyword_action', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id');
      $table->string('keyword');
      $table->boolean('is_private');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('user_keyword_action');
  }
}
