<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewerAttributeTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('viewer_attribute', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('viewer_id');
      $table->integer('attribute_id');
      $table->text('value');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('viewer_attribute');
  }
}
