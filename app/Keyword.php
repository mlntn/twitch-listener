<?php

namespace App;

use App\Exceptions\KeywordNotFoundException;
use App\Models\Keyword as KeywordModel;
use App\Services\Chatter;
use App\Services\TwitchConnector;

abstract class Keyword {

  public static function call($channel, $keyword, $user, Chatter $chatter, $params) {
    try {
      $kw = KeywordModel::findByChannelKeyword($channel, $keyword);

      if (self::hasValidRole($user, $channel, $kw->role_mask)) {
        list($class, $method) = explode('@', $kw->method);
        $class = '\App\Plugins\\' . $class;

        $plugin = new $class($chatter, $kw, $channel, $user);

        call_user_func_array([$plugin, $method], $params);
      }
    }
    catch (KeywordNotFoundException $e) {
      // noop
    }
  }

  protected static function hasValidRole($user, $channel, $role_mask) {
    if (1 & $role_mask) return true;

    $twitch = new TwitchConnector;

    $role = $twitch->getUserRoleMask($user, substr($channel, 1));

    return $role & $role_mask > 0;
  }

}