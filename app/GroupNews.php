<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupNews extends Model
{
    protected  $table = 'group_news';
    public  $timestamps = true;
    public $primaryKey = 'id';
    protected $fillable = [
        'group_id' , 'title' , 'details' , 'cover_image' , 'city_id','user_id'
    ];
}
