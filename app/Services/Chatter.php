<?php

namespace App\Services;

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
    $parts = explode(' ', $message);

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
          $params  = array_slice($parts, 4);
          $this->keyword($parts[2], $keyword, $params, $user, $is_whisper);
        }
        break;
      case '353':
        $channel = $parts[4];
        $this->channels[$channel] = $channel;
        echo "Joined {$channel}\n";
    }
  }

  private function getUser($ircuser) {
    return preg_replace('~^:\w+!(\w+)@\w+.tmi.twitch.tv$~', '$1', $ircuser);
  }

  private function keyword($channel, $keyword, $params, $user, $whisper = false) {
    $class    = "\\App\\Keywords\\" . ($whisper ? 'Whispers\\' : '') . $keyword . 'Keyword';

    if (class_exists($class) === false) {
      return false;
    }

    $instance = new $class($this, $channel, $user);

    array_walk($params, function(&$v) {
      $v = trim($v);
    });

    try {
      return call_user_func_array([$instance, 'handle'], $params);
    }
    catch (\Exception $e) {
      // noop
    }
  }

  private function parseMessage($parts) {
    return trim(substr(implode(' ', array_slice($parts, 3)), 1));
  }

}