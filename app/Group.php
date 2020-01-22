<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    public $timestamps = true;
    public $primaryKey = 'id';
    protected $fillable = [
        'name',
        'city_id',
        'photo',
        'admin',
        'activity_id',
        'about_me',
        'active'
    ];
}
