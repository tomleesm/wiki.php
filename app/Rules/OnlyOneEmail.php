<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class OnlyOneEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 在表格 users 中已有這個使用者，而且只有一個
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return User::where('email', $value)->count() === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not registered E-mail. <a href="/register">Create account ?</a>';
    }
}
