<?php

namespace App\Actions;

use App\Models\ClassworkTask;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class DeleteClassworkTask
{
    /**
     * Create a newly registered classwork task.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ClassworkTask  $classwork_task
     * @return void
     */
    public function delete(User $user, ClassworkTask $classwork_task)
    {
        // ユーザー権限の認証
        Gate::forUser($user)->authorize('delete', $classwork_task);

        $classwork_task->delete();
    }
}
