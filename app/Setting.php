<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // for use firstOrNew()
    protected $fillable = ['key'];
}
