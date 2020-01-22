<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'user_id', 'type', 'message', 'title','contact_id','city_id' , 'group_id'
    ];
}
