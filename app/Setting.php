<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $fillable=[
        'bank_name',
        'account_number',
        'phone_number',
    ];
}
