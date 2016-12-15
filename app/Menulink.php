<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menulink extends Model
{
    protected $fillable = [
        'group_id', 'user_id', 'menu_id'
    ];

    public function menugroup()
    {
        return $this->belongsTo('App\Menugroup','group_id','id');
    }

    public function menuitem()
    {
        return $this->belongsTo('App\Menuitem','menu_id','id');
    }
}
