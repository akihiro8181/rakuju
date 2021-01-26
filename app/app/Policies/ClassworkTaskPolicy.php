<?php

namespace App\Policies;

use App\Models\ClassworkTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassworkTaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ClassworkTask  $classworkTask
     * @return mixed
     */
    public function view(User $user, ClassworkTask $classworkTask)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ClassworkTask  $classworkTask
     * @return mixed
     */
    public function create(User $user, ClassworkTask $classworkTask)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ClassworkTask  $classworkTask
     * @return mixed
     */
    public function update(User $user, ClassworkTask $classworkTask)
    {
        // ユーザーが管理者かつ管理している学校の場合
        if (in_array($user->roll_flag, ['ad'])) {
            foreach ($user->school->classworks as $classwork) {
                if ($classwork->id == $classworkTask->in_charge->classwork->id) {
                    return true;
                }
            }
        // ユーザーが教師かつ担当の授業の場合
        } else if (in_array($user->roll_flag, ['te'])) {
            // 主任
            foreach ($user->in_charges as $in_charge) {
                foreach ($in_charge->classwork_tasks as $classwork_task) {
                    if($classwork_task->id == $classworkTask->id) {
                        return true;
                    }
                }
            }
            // チューター
            foreach ($user->tutors as $in_charge) {
                foreach ($in_charge->classwork_tasks as $classwork_task) {
                    if($classwork_task->id == $classworkTask->id) {
                        return true;
                    }
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ClassworkTask  $classworkTask
     * @return mixed
     */
    public function delete(User $user, ClassworkTask $classworkTask)
    {
        // ユーザーが管理者かつ管理している学校の場合
        if (in_array($user->roll_flag, ['ad'])) {
            foreach ($user->school->classworks as $classwork) {
                if ($classwork->id == $classworkTask->in_charge->classwork->id) {
                    return true;
                }
            }
        // ユーザーが教師かつ担当の授業の場合
        } else if (in_array($user->roll_flag, ['te'])) {
            // 主任
            foreach ($user->in_charges as $in_charge) {
                foreach ($in_charge->classwork_tasks as $classwork_task) {
                    if($classwork_task->id == $classworkTask->id) {
                        return true;
                    }
                }
            }
            // チューター
            foreach ($user->tutors as $in_charge) {
                foreach ($in_charge->classwork_tasks as $classwork_task) {
                    if($classwork_task->id == $classworkTask->id) {
                        return true;
                    }
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ClassworkTask  $classworkTask
     * @return mixed
     */
    public function restore(User $user, ClassworkTask $classworkTask)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ClassworkTask  $classworkTask
     * @return mixed
     */
    public function forceDelete(User $user, ClassworkTask $classworkTask)
    {
        //
    }
}
