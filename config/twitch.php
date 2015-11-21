<?php

// Setup at: http://www.twitch.tv/kraken/oauth2/clients

return [

  'client_id' => env('TWITCH_CLIENT'),

  'secret' => env('TWITCH_SECRET'),

  'redirect_uri' => 'http://localhost:8000',

];