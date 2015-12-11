<?php

namespace App\Models;

use App\Exceptions\KeywordNotFoundException;
use App\Exceptions\UserNotFoundException;
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

  public function setUser($channel) {
    try {
      $user = preg_replace('~^#~', '', $channel);
      $u = User::whereTwitchUsername($user)->firstOrFail();
      $this->user_id = $u->id;
    }
    catch (ModelNotFoundException $e) {
      throw new UserNotFoundException;
    }
  }

}
