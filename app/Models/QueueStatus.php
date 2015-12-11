<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueStatus extends Model {

  const NONE        = 1;
  const IN_PROGRESS = 2;
  const COMPLETED   = 3;
  const SKIPPED     = 4;
  const DELETED     = 5;

  protected $table = 'queue_status';

  protected $fillable = [
    'name',
  ];

}
