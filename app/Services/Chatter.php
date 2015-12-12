<?php

namespace App\Services;

use App\Keyword;

class Chatter {

  public $socket;
  public $channels = [];

  private $private;

  public function __construct($private = false) {
    ini_set('output_buffering', 0);
    $this->private = empty($private) === false;
    list($server, $port) = explode(':', $this->getServer());
    $this->socket = fsockopen($server, $port);
  }

  public function getServer() {
    if ($this->private) {
      $data = json_decode(file_get_contents('http://tmi.twitch.tv/servers?cluster=group'));

      return array_shift($data->servers);
    }

    return config('irc.server');
  }

  public function login($username, $password) {
    $this->send('PASS', 'oauth:' . $password);
    $this->send('NICK', $username);
  }

  public function listen() {
    stream_set_timeout($this->socket, 1);

    while (true) {
      while ($message = fgets($this->socket)) {
        $this->handle($message);

        flush();
      }
    }
  }


  public function send($cmd, $msg = null) {
    if ($msg == null) {
      fputs($this->socket, $cmd . "\r\n");
      echo "{$cmd}\n";
    }
    else {
      $msg = trim($msg);
      fputs($this->socket, $cmd . ' ' . $msg . "\r\n");
      if (in_array($cmd, ['PASS', 'NICK', 'PRIVMSG']) === false) {
        echo "{$cmd} {$msg}\n";
      }
    }

  }

  public function say($message, $channel) {
    $this->send('PRIVMSG', "{$channel} :{$message}");
    echo " -> {$message}";
  }

  public function whisper($message, $channel, $user) {
    $this->send("PRIVMSG", "{$channel} :/w {$user} {$message}");
    echo " -> *{$user}: {$message}";
  }

  public function join($channels) {
    $channels = is_array($channels) ? $channels : [ $channels ];

    foreach ($channels as $channel) {
      $this->send('JOIN', "#{$channel}");
      if ($this->private) {
        $this->send('CAP REQ', 'twitch.tv/commands');
      }
    }
  }

  private function handle($message) {
    $parts = str_getcsv($message, ' ', '"', '\\');

    echo $message . PHP_EOL;

    if ($parts[0] == 'PING') {
      $this->send('PONG', $parts[1]);
      echo "Ping received\n";

      return;
    }

    switch ($parts[1]) {
      case 'PRIVMSG':
      case 'WHISPER':
        echo $message . "\n";
        $user = $this->getUser($parts[0]);
        $message = $this->parseMessage($parts);
        $is_whisper = $parts[1] === 'WHISPER';
        echo ($is_whisper ? '*' : '') . "{$user}: {$message}\n";
        if (preg_match('~^:\!(\w+)\b~', $parts[3], $pieces)) {
          $keyword = $pieces[1];
          $channel = $parts[2];
          $params = array_slice($parts, 4);
          array_walk($params, function(&$v){ $v = str_replace('\\"', '"', $v); });
          try {
            Keyword::call($channel, $keyword, $user, $this, $params);
          }
          catch (\Exception $e) {
            // noop if anything breaks
            // die(var_dump($e)); // in case we need to debug
          }
        }
        break;
      case '353':
        $channel = $parts[4];
        $this->channels[$channel] = $channel;
        echo "Joined {$channel}\n";
        break;
    }
  }

  private function getUser($ircuser) {
    return preg_replace('~^:\w+!(\w+)@\w+.tmi.twitch.tv$~', '$1', $ircuser);
  }

  private function parseMessage($parts) {
    return trim(substr(implode(' ', array_slice($parts, 3)), 1));
  }

}