<?php

namespace App\Rules;

use DB;
use Illuminate\Contracts\Validation\Rule;

class EmailExistsInMultipleTables implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tables = ['users', 'operator_kasir', 'user_member', 'karyawan'];
        foreach ($tables as $table) {
            if (DB::table($table)->where('email', $value)->exists()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
