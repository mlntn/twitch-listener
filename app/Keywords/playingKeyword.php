<?php

namespace App\Keywords;

use App\Keyword;
use App\Models\Code;
use App\Models\Status;

class playingKeyword extends Keyword {

  public function handle() {
    try {
      $code = Code::where('status_id', Status::PLAYING)->orderBy('updated_at')->firstOrFail();
      $this->chatter->say("CURRENTLY PLAYING: {$code->code} by {$code->user} (started {$code->created_at->diffForHumans()})", $this->channel);
    }
    catch (\Exception $e) {
      $this->chatter->say("Not currently playing anything.", $this->channel);
    }
  }

}