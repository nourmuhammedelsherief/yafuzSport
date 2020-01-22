<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    protected $table = 'group_chats';
    public $timestamps = true;
    public $primaryKey = 'id';
    protected $fillable = [
        'message' , 'group_id'
    ];
}
