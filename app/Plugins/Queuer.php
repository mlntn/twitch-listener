<?php

namespace App\Plugins;

use App\Exceptions\UserNotFoundException;
use App\Models\Queue;
use App\Models\User;
use App\Plugin;

class Queuer extends Plugin {

  public function add($item, $name = null, $description = null) {
    try {
      $user_id = User::findByChannel($this->channel)->id;
      $user = $this->user;

      Queue::create(compact('user_id', 'user', 'item', 'name', 'description'));

      $this->say("{$user} added {$item}" . ($name ? " - {$name}" : '') . ($description ? " - {$description}" : ''));
    }
    catch (UserNotFoundException $e) {
      // noop
    }
  }

}