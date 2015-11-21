<?php

namespace App\Keywords;

use App\Keyword;

class hello extends Keyword {

  public function handle($name) {
    $this->chatter->say("Hello, {$name}", $this->channel);
  }

}