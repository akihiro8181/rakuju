<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class AddUser
{

    use Fortify\PasswordValidationRules;

    /**
     * Update a newly registered School Information.
     *
     * @param  App\Models\User  $user
     * @param  array  $input
     * @return void
     */
    public function add(User $current_user, array $input)
    {

        // dd($input);

        // Validator::make($input, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'login_number' => ['required', 'string', 'max:255'],
        //     'password' => $this->passwordRules(),
        // ])->validate();

        User::create([
            'name' => $input['name'],
            'school_id' => $current_user->school_id,
            'roll_flag' => $input['roll_flag'],
            'login_number' => $input['login_number'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
