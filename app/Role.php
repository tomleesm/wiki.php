<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const LOGIN_USER = 1;
    const EDITOR = 2;
    const ADMINISTRATOR = 3;
}
