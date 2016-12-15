<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weblog extends Model
{
    protected $table = 'weblogs';

    protected $fillable = [
        'user_id', 'level', 'method', 'require_data',
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

    public function account()
    {
        return $this->hasOne('App\Account', 'id', 'user_id');
    }
}
