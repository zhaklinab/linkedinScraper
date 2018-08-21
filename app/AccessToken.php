<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{

   protected $fillable = ['access_token', 'expires_in'];
}
