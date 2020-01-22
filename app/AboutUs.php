<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    //
    protected $table="about_us";
    protected $fillable=['title','content'];
}
