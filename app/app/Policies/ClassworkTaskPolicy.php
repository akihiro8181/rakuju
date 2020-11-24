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
     * @param  \App\Models\InCharge  $in_charge
     * @return mixed
     */
    public function create(User $user, InCharge $in_charge)
    {
        if (in_array($user->roll_flag, ['te', 'ad'])) {
            foreach ($user->in_charges as $item) {
                if($item->id == $in_charge->id) {
                    return true;
                }
            }

            foreach ($user->tutors as $item) {
                if($item->id == $in_charge->id) {
                    return true;
                }
            }
        }

        return false;
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
        //
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
        //
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
