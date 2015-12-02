<?php

namespace App\Keywords;

use App\Keyword;
use App\Models\Code;
use App\Models\Status;

class nextKeyword extends Keyword {

  public function handle() {
    try {
      $code = Code::where('status_id', Status::WAITING)->orderBy('created_at')->firstOrFail();
      $this->chatter->say(trans('code.next.success', ['code'=>$code->code, 'user'=>$code->user, 'submitted'=>$code->created_at->diffForHumans()]), $this->channel);
    }
    catch (\Exception $e) {
      $this->chatter->say(trans('code.next.failure'), $this->channel);
    }
  }

}