<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'school_name' => ['required', 'string', 'max:255'],
            'school_admin_name' => ['required', 'string', 'max:255'],
            'workspace_URL' => ['required', 'string', 'max:255', 'unique:schools,workspace_url'],
            'login_number' => ['required', 'regex:/^\s*-?[0-9,]+\s*$/', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {

            $school = tap(School::create([
                'name' => $input['school_name'],
                'workspace_url' => $input['workspace_URL'],
            ]))->update([
                'name' => $input['school_name'],
                'workspace_url' => $input['workspace_URL'],
            ]);


            return tap(User::create([
                'name' => $input['school_admin_name'],
                'school_id' => $school->id,
                'login_number' => $input['login_number'],
                'password' => Hash::make($input['password']),
                'roll_flag' => 'ad',
            ]), function (User $user) {
                $this->createTeam($user);
            });

        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
