<?php

namespace App\Models;

use App\Exceptions\UserNotFoundException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

  use Authenticatable, Authorizable, CanResetPassword;

  protected $table = 'user';

  protected $fillable = ['name', 'twitch_username', 'twitch_token', 'twitch_email', 'twitch_logo'];

  protected $hidden = ['password', 'remember_token'];

  public static function findByChannel($channel) {
    try {
      $user = preg_replace('~^#~', '', $channel);

      return static::whereTwitchUsername($user)->firstOrFail();
    }
    catch (ModelNotFoundException $e) {
      throw new UserNotFoundException;
    }
  }
  
}
