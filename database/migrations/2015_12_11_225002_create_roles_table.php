<?php

use App\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('role', function (Blueprint $table) {
      $table->integer('id');
      $table->string('name');
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Role::create(['name' => 'Viewer', 'id' => 2 ** 0]);
    Role::create(['name' => 'Follower', 'id' => 2 ** 1]);
    Role::create(['name' => 'Subscriber', 'id' => 2 ** 2]);
    Role::create(['name' => 'Moderator', 'id' => 2 ** 3]);
    Role::create(['name' => 'Owner', 'id' => 2 ** 4]);
    Role::create(['name' => 'Staff', 'id' => 2 ** 5]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('role');
  }
}
