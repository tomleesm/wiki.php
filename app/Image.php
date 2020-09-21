<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * primary key is UUID v1
     */
    public $incrementing = false;
    protected $keyType = 'string';
}
