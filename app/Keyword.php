<?php

namespace App;

use App\Services\Chatter;

abstract class Keyword {

  /**
   * @var Chatter
   */
  protected $chatter;
  protected $channel;
  protected $user;

  public function __construct(Chatter $chatter, $channel, $user) {
    $this->chatter = $chatter;
    $this->channel = $channel;
    $this->user = $user;
  }
}