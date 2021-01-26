<?php

namespace App\Actions;

use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Rules\Password; // passwordのバリデーションの暫定対処用

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
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'login_number' => ['required', 'regex:/^\s*-?[0-9,]+\s*$/', 'max:255'],
            // passwordRules()の'confirmed'の条件を満たすには$input['password_confirmation']が必要
            // 追加は後回しにして暫定対処
            // 'password' => $this->passwordRules(),
            'password' => ['required', 'string', new Password],
        ])->validate();

        if (User::where('school_id', $current_user->school_id)->where('login_number', $input['login_number'])->exists()) {
            return;
        }

        User::create([
            'name' => $input['name'],
            'school_id' => $current_user->school_id,
            'roll_flag' => $input['roll_flag'],
            'login_number' => $input['login_number'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
