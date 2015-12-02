<?php

namespace App\Keywords;

use App\Keyword;
use App\Models\Code;
use App\Models\Status;

class addKeyword extends Keyword {

  public function handle($code) {
    try {
      $code     = Code::create(['user' => $this->user, 'code' => $code]);
      $position = Code::where('id', '<=', $code->id)->where('status_id', Status::WAITING)->orderBy('created_at')->count();
      $this->chatter->say(trans('code.add.success', ['user'=>$this->user, 'position'=>$position]), $this->channel);
    }
    catch (\Exception $e) {
      $this->chatter->say(trans('code.add.failure'), $this->channel);
    }
  }

}