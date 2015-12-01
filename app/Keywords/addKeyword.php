<?php

namespace App\Keywords;

use App\Keyword;
use App\Models\Code;
use App\Models\Status;

class addKeyword extends Keyword {

  public function handle($code) {
    $code = Code::create(['user'=>$this->user, 'code'=>$code]);
    $position = Code::where('id', '<=', $code->id)->where('status_id', Status::WAITING)->orderBy('created_at')->count();
    $this->chatter->say("Thanks for the submission, {$this->user}! You are #{$position} in the queue.", $this->channel);
  }

}