<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menuitem extends Model
{
    protected $table = 'menuitems';

    protected $fillable = [
        'name', 'url', 'description',
    ];
}
