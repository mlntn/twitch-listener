<?php

namespace App\Console\Commands;

use App\Services\Chatter;
use App\User;
use Illuminate\Console\Command;

class ChatCommand extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'chat {private=0}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Start Twitch chat listener';

  public function __construct() {
    set_time_limit(0);

    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $chatter = new Chatter($this->argument('private'));

    foreach (User::all() as $u) {
      $chatter->login($u->twitch_username, $u->twitch_token);
      $chatter->join($u->twitch_username);
    }
    $chatter->listen();
  }

}
