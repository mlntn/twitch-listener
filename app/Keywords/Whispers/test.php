<?php

namespace App\Keywords\Whispers;

use App\Keyword;

class test extends Keyword {

  public function handle($name) {
    $this->chatter->whisper("Testing, {$name}", $this->channel, $this->user);
  }

}