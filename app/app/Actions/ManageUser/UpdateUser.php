<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class UpdateUser
{
    /**
     * Update a newly registered School Information.
     *
     * @param  App\Models\User  $user
     * @param  array  $input
     * @return void
     */
    public function update(User $current_user, User $selected_user, array $input)
    {

        $selected_user->update([
            'name' => $input['name'],
            'roll_flag' => $input['roll_flag'],
            'login_number' => $input['login_number'],
        ]);
    }
}
