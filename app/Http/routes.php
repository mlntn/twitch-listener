<?php

use App\Services\TwitchConnector;
use App\User;

Route::get('/', function (TwitchConnector $twitch) {
  $registration = $twitch->complete(request('code'), request('state'));
  $user         = $twitch->user();

  User::create([
    'name' => $user->display_name,
    'twitch_username' => $user->name,
    'twitch_token' => $registration->access_token,
    'twitch_email' => $user->email,
    'twitch_logo' => $user->logo,
  ]);

  die('Done!');
});

Route::get('/register', function (TwitchConnector $twitch) {
  return $twitch->register(['chat_login', 'user_read', 'channel_check_subscription']);
});
