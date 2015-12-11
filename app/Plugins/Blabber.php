<?php

namespace App\Plugins;

use App\Exceptions\KeywordNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Models\Keyword;
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
        $kw->setUser($this->channel);
        $kw->keyword = $keyword;
      }
      $kw->method = 'Blabber@blab';
      $kw->text = $text;

      $kw->save();

      $this->say("ADDED: !{$keyword} -> {$text}");
    }
    catch (UserNotFoundException $e) {
      // noop, I guess
    }
  }

  public function remove($keyword) {
    try {
      $kw = Keyword::findByChannelKeyword($this->channel, $keyword);
      if ($kw->method !== 'Blabber@blab') {
        return;
      }

      $text = $kw->text;

      $kw->destroy();

      $this->say("REMOVED: !{$keyword} -> {$text}");
    }
    catch (KeywordNotFoundException $e) {
      // noop
    }
  }

}