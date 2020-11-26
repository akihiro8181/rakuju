<?php

namespace App\Actions;

use App\Models\ClassworkTask;
use App\Models\User;
use App\Models\InCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class UpdateClassworkTask
{
    /**
     * Create a newly registered classwork task.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\InCharge  $in_charge
     * @param  array  $input
     * @return \App\Models\User
     */
    public function update(User $user, InCharge $in_charge, ClassworkTask $classwork_task, array $input)
    {
        // ユーザー権限の認証
        // Gate::forUser($user)->authorize('create', $in_charge);

        // Validator::make($input, [
        //     'in_charge_id' => ['required', 'numeric'],
        //     'name' => ['required', 'string', 'max:255'],
        //     'deadline' => ['required', 'date', 'max:255'],
        //     'sort_num' => ['required', 'numeric', 'max:255'],
        //     'contents' => ['required', 'string'],
        // ])->validate();

        $classwork_task->update([
            'in_charge_id' => $in_charge->id,
            'name' => $input['name'],
            'deadline' => $input['deadline'],
            'sort_num' => $input['sort_num'],
            'contents' => json_decode($input['contents']),
        ]);
    }
}
