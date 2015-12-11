<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('keyword', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id');
      $table->string('keyword');
      $table->string('method');
      $table->text('text')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('keyword');
  }
}
