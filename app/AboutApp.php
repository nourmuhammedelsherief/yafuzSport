<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutApp extends Model
{
    protected $table = 'about_apps';
    public $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'address', 'phone_number' , 'email'
    ];
}
