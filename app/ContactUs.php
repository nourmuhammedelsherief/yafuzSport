<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';
    public $timestamps = true;
    public $primaryKey = 'id';
    protected $fillable= [
        'name' , 'email' , 'description' , 'user_id'
    ];

}
