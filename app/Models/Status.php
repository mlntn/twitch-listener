<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model {

  const WAITING  = 1;
  const PLAYING  = 2;
  const FINISHED = 3;
  const SKIPPED  = 4;

  protected $table = 'status';
  protected $fillable = ['name'];

}
