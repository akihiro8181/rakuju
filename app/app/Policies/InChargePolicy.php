<?php

namespace App\Policies;

use App\Models\InCharge;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InChargePolicy
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
     * @param  \App\Models\InCharge  $inCharge
     * @return mixed
     */
    public function view(User $user, InCharge $inCharge)
    {
        // ユーザーが管理者かつ管理している学校の場合
        if (in_array($user->roll_flag, ['ad'])) {
            foreach ($user->school->classworks as $classwork) {
                if ($classwork->id == $inCharge->classwork->id) {
                    return true;
                }
            }
        // ユーザーが教師かつ担当の授業の場合
        } else if (in_array($user->roll_flag, ['te'])) {
            // 主任
            foreach ($user->in_charges as $item) {
                if($item->id == $inCharge->id) {
                    return true;
                }
            }
            // チューター
            foreach ($user->tutors as $item) {
                if($item->id == $inCharge->id) {
                    return true;
                }
            }
        // ユーザーが生徒かつ受講中の授業の場合
        } else if (in_array($user->roll_flag, ['st'])) {
            foreach ($user->attendances as $item) {
                if($item->id == $inCharge->id) {
                    return true;
                }
            }
        } else {
            return false;
        }
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
        // ユーザーが管理者かつ管理している学校の場合
        if (in_array($user->roll_flag, ['ad'])) {
            foreach ($user->school->classworks as $classwork) {
                if ($classwork->id == $in_charge->classwork->id) {
                    return true;
                }
            }
        // ユーザーが教師かつ担当の授業の場合
        } else if (in_array($user->roll_flag, ['te'])) {
            // 主任
            foreach ($user->in_charges as $item) {
                if($item->id == $in_charge->id) {
                    return true;
                }
            }
            // チューター
            foreach ($user->tutors as $item) {
                if($item->id == $in_charge->id) {
                    return true;
                }
            }
        } else {
            return false;
        }
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
