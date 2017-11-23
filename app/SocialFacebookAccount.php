<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialFacebookAccount extends Model
{
    protected $fillable = ['user_id', 'provider_user_id', 'provider', 'token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}