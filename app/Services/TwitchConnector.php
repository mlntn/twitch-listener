<?php

namespace App\Services;

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

}