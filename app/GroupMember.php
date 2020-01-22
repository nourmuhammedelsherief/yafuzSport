<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'group_members';
    public $timestamps = true;
    public $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'group_id',
        'city_id',
        'super_visor',
        'accepted',
        'is_join'
    ];
}
