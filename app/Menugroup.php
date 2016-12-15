<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menugroup extends Model
{
    protected $fillable = [
        'name', 'description',
    ];

    public function menulink()
    {
        return $this->hasMany('App\Menulink','group_id','id');
    }
}
