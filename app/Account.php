<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function weblog()
    {
        return $this->belongsTo('App\Weblog', 'user_id');
    }
}
