<?php

namespace App\Plugins;

use App\Plugin;
use App\Services\Strawpoll;

class Poller extends Plugin {

  public function add($title, ...$options) {
    $helper_text = "- !{$this->keyword->keyword} TITLE OPTION OPTION...";
    if (empty($title)) {
      $this->say("You need to have a title for your poll {$helper_text}");
      return;
    }
    if (empty($options)) {
      $this->say("You need to add some options for your poll {$helper_text}");
      return;
    }

    $poll = Strawpoll::add($title, $options);

    $this->say("NEW POLL: {$poll->title} http://strawpoll.me/{$poll->id}");
  }

}