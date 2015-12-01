<?php

namespace App\Keywords;

use App\Keyword;
use App\Models\Code;
use App\Models\Status;

class nextKeyword extends Keyword {

  public function handle() {
    try {
      $code = Code::where('status_id', Status::WAITING)->orderBy('created_at')->firstOrFail();
      $this->chatter->say("NEXT LEVEL: {$code->code} by {$code->user} (submitted {$code->created_at->diffForHumans()})", $this->channel);
    }
    catch (\Exception $e) {
      $this->chatter->say("The queue is empty.", $this->channel);
    }
  }

}