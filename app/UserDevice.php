<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    //
    protected $table='user_devices';
    protected $fillable = [
        'user_id', 'device_type', 'device_token', 'is_open'
    ];
}
