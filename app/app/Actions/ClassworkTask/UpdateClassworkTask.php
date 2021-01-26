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
     * @param  App\Models\ClassworkTask  $classwork_task
     * @param  array  $input
     * @return void
     */
    public function update(User $user, ClassworkTask $classwork_task, array $input)
    {
        // ユーザー権限の認証
        Gate::forUser($user)->authorize('update', $classwork_task);

        // Validator::make($input, [
        //     'in_charge_id' => ['required', 'numeric'],
        //     'name' => ['required', 'string', 'max:255'],
        //     'deadline' => ['required', 'date', 'max:255'],
        //     'sort_num' => ['required', 'numeric', 'max:255'],
        //     'contents' => ['required', 'string'],
        // ])->validate();

        $classwork_task->update([
            'name' => $input['name'],
            'sort_num' => $input['sort_num'],
            'contents' => json_decode($input['contents']),
        ]);
    }
}
