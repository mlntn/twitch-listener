<?php

return [
  'playing' => [
    'success' => "CURRENTLY PLAYING: :code by :user (started :started)",
    'failure' => "Not currently playing anything.",
  ],
  'next' => [
    'success' => "NEXT LEVEL: :code by :user (submitted :submitted)",
    'failure' => "The queue is empty.",
  ],
  'spot' => [
    'success' => "There is 1 level ahead of yours, :user.|There are :count levels ahead of yours, :user.",
    'failure' => "You don't have anything in the queue, :user.",
  ],
  'add' => [
    'success' => "Thanks for the submission, :user! You are #:position in the queue.",
    'failure' => "That's not a valid level code, :user.",
  ],
];