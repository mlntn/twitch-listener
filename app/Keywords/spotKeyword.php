<?php

namespace App\Keywords;

use App\Keyword;
use App\Models\Code;
use App\Models\Status;

class spotKeyword extends Keyword {

  public function handle() {
    try {
      $code  = Code::whereUser($this->user)->where('status_id', Status::WAITING)->orderBy('created_at')->firstOrFail();
      $count = Code::where('id', '<', $code->id)->where('status_id', Status::WAITING)->orderBy('created_at')->count();
      $this->chatter->say("There " . ($count === 1 ? 'is' : 'are') . " {$count} level" . ($count === 1 ? '' : 's') . " ahead of yours, {$this->user}.", $this->channel);
    }
    catch (\Exception $e) {
      $this->chatter->say("You don't have anything in the queue, {$this->user}.", $this->channel);
    }
  }

}