<?php

namespace App;

use App\Models\Keyword as KeywordModel;
use App\Services\Chatter;

abstract class Plugin {

  /**
   * @var KeywordModel
   */
  protected $keyword;

  public function __construct(Chatter $chatter, KeywordModel $keyword, $channel, $user) {
    $this->chatter = $chatter;
    $this->keyword = $keyword;
    $this->channel = $channel;
    $this->user = $user;
  }

  public function say($message) {
    $this->chatter->say($message, $this->channel);
  }

}