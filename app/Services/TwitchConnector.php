<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class TwitchConnector {

  public $token;

  public function register($scopes = []) {
    $params = [
      'client_id' => config('twitch.client_id'),
      'response_type' => 'code',
      'redirect_uri' => config('twitch.redirect_uri'),
      'scope' => implode(' ', $scopes),
      'state' => md5(time()),
    ];

    $url = "https://api.twitch.tv/kraken/oauth2/authorize?" . http_build_query($params);

    return redirect()->to($url);
  }

  public function complete($code, $state) {
    $params = [
      'client_id' => config('twitch.client_id'),
      'client_secret' => config('twitch.secret'),
      'grant_type' => 'authorization_code',
      'redirect_uri' => config('twitch.redirect_uri'),
      'code' => $code,
      'state' => $state,
    ];

    $ch = curl_init('https://api.twitch.tv/kraken/oauth2/token');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($data);

    if (isset($data->error)) {
      throw new \Exception($data->message, $data->status);
    }

    $this->token = $data->access_token;

    return $data;
  }

  public function user() {
    return $this->get('user');
  }

  public function getUserRoleMask($user, $channel) {
    $ch = curl_init("http://tmi.twitch.tv/group/user/{$channel}/chatters");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    curl_close($ch);

    $chatters = json_decode($json);

    $map = [
      'moderators' => Role::MODERATOR,
      'staff' => Role::STAFF,
    ];

    $role_mask = 1; // everyone is a viewer

    foreach ((array) $chatters->chatters as $k=>$users) {
      if (in_array($user, $users) && array_key_exists($k, $map)) {
        $role_mask += $map[$k];
      }
    }

    if ($user === $channel) {
      $role_mask += Role::OWNER;
    }

    $this->setTokenFromChannel($channel);
    $response = $this->get("channels/{$channel}/subscriptions/{$user}");
    if(isset($response->status) === false) {
      $role_mask += Role::SUBSCRIBER;
    }

    $this->setTokenFromChannel($channel);
    $response = $this->get("users/{$user}/follows/channels/{$channel}");
    if(isset($response->status) === false) {
      $role_mask += Role::FOLLOWER;
    }

    return $role_mask;
  }

  public function checkUserRole($user, $channel, $role) {
    switch ($role) {
      case Role::VIEWER:
        return true;
      case Role::FOLLOWER:
        $response = $this->get("users/{$user}/follows/channels/{$channel}");

        return isset($response->status) === false;
      case Role::SUBSCRIBER:
        $this->setTokenFromChannel($channel);
        $response = $this->get("channels/{$channel}/subscriptions/{$user}");

        return isset($response->status) === false;
      case Role::MODERATOR:
        return $this->checkChatters($channel, $user, 'moderators');
      case Role::OWNER:
        return $user === $channel;
      case Role::STAFF:
        return $this->checkChatters($channel, $user, 'staff');
      default:
        return false;
    }
  }

  private function checkChatters($channel, $user, $group) {
    $json = file_get_contents("http://tmi.twitch.tv/group/user/{$channel}/chatters");
    $chatters = json_decode($json);

    return in_array($user, $chatters->chatters->$group);
  }

  private function get($uri) {
    $ch = curl_init('https://api.twitch.tv/kraken/' . $uri);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Accept: application/vnd.twitchtv.v3+json",
      "Authorization: OAuth {$this->token}"
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($ch);

    return json_decode($data) ?: $data;
  }

  private function setTokenFromChannel($channel) {
    $user = User::whereTwitchUsername($channel)->firstOrFail();

    $this->token = $user->twitch_token;
  }

}