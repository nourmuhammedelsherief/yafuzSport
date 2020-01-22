<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    public $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['name'];
}
