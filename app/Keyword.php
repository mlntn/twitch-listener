<?php

namespace App;

use App\Exceptions\KeywordNotFoundException;
use App\Models\Keyword as KeywordModel;
use App\Services\Chatter;

abstract class Keyword {

  public static function call($channel, $keyword, $user, Chatter $chatter, $params) {
    try {
      $kw = KeywordModel::findByChannelKeyword($channel, $keyword);
      list($class, $method) = explode('@', $kw->method);
      $class = '\App\Plugins\\' . $class;

      $plugin = new $class($chatter, $kw, $channel, $user);

      call_user_func_array([$plugin, $method], $params);
    }
    catch (KeywordNotFoundException $e) {
      echo "{$channel}: What's {$keyword}?";
    }
  }

}