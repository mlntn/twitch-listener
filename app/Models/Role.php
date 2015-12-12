<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

  const VIEWER     = 1;
  const FOLLOWER   = 2;
  const SUBSCRIBER = 4;
  const MODERATOR  = 8;
  const OWNER      = 16;
  const STAFF      = 32;

  protected $table = 'role';

  protected $fillable = [
    'id',
    'name',
    'description',
  ];

}
