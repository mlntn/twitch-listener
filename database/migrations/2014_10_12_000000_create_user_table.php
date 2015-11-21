<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('user', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('twitch_username');
      $table->string('twitch_token');
      $table->string('twitch_email');
      $table->string('twitch_logo')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('user');
  }
}
