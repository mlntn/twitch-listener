<?php

namespace App\Models;

use App\Exceptions\KeywordNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Keyword extends Model {

  protected $table = 'keyword';

  public static function findByChannelKeyword($channel, $keyword) {
    try {
      $user = preg_replace('~^#~', '', $channel);
      return static::join('user', 'user_id', '=', 'user.id')->whereTwitchUsername($user)->whereKeyword($keyword)->firstOrFail();
    }
    catch (ModelNotFoundException $e) {
      throw new KeywordNotFoundException;
    }
  }

}
