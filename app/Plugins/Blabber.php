<?php

namespace App\Plugins;

use App\Exceptions\KeywordNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Models\Keyword;
use App\Models\User;
use App\Plugin;

class Blabber extends Plugin {

  public function blab() {
    $this->say($this->keyword->text);
  }

  public function add($keyword, $text) {
    try {
      try {
        $kw = Keyword::findByChannelKeyword($this->channel, $keyword);
      }
      catch (KeywordNotFoundException $e) {
        $kw = new Keyword;
        $kw->user_id = User::findByChannel($this->channel)->id;
        $kw->keyword = $keyword;
      }
      $kw->method = 'Blabber@blab';
      $kw->text = $text;

      $kw->save();

      $this->say("ADDED: !{$keyword} -> {$text}");
    }
    catch (UserNotFoundException $e) {
      // noop
    }
  }

  public function remove($keyword) {
    try {
      $kw = Keyword::findByChannelKeyword($this->channel, $keyword);
      if ($kw->method === 'Blabber@blab') {
        $text = $kw->text;

        $kw->destroy();

        $this->say("REMOVED: !{$keyword} -> {$text}");
      }
    }
    catch (KeywordNotFoundException $e) {
      // noop
    }
  }

}