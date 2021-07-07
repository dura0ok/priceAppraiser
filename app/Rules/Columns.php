<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Columns implements Rule
{
    /**
     * @var string
     */
    private $re;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->re = '/([a-z];){3}[a-z]/i';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool)preg_match($this->re, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return ':attribute должен быть заполнен в слудющем формате A;B;C;D';
    }
}
