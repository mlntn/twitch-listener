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
      $this->chatter->say(trans_choice('code.spot.success', $count, ['count'=>$count, 'user'=>$this->user]), $this->channel);
    }
    catch (\Exception $e) {
      $this->chatter->say(trans('code.spot.failure', ['user'=>$this->user]), $this->channel);
    }
  }

}