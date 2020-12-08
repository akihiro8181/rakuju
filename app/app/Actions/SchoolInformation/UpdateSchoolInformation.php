<?php

namespace App\Actions;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class UpdateSchoolInformation
{
    /**
     * Update a newly registered School Information.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\School  $school
     * @param  array  $input
     * @return void
     */
    public function update(User $user, School $school, array $input)
    {

        $school->update([
            'name' => $input['name'],
            'workspace_url' => $input['workspace_url'],
        ]);
    }
}
