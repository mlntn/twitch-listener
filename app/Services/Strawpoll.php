<?php

namespace App\Services;

use App\Plugin;
use Guzzle\Http\Client;
use GuzzleHttp\Psr7\Request;

class Strawpoll {

  public static function add($title, $options = []) {
    $request = json_encode([
      'title' => $title,
      'options' => $options,
    ]);

    // Get cURL resource
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_URL => 'http://strawpoll.me/api/v2/polls',
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $request,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($request)
      ],
    ]);

    $json = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($json);

    return $response;
  }

}