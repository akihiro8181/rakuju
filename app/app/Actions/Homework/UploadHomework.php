<?php

namespace App\Actions;

use App\Models\ClassworkTask;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\UploadedFile;

class UploadHomework
{
    /**
     * ファイルを保存する
     *
     * @param  App\Models\User  $user
     * @param  App\Models\InCharge  $in_charge
     * @param  Illuminate\Http\UploadedFile  $file
     * @return \App\Models\User
     */
    public function upload(User $user, ClassworkTask $classwork_task, UploadedFile $file)
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

        $file->storeAs('public/' . $classwork_task->id . "/" . $user->id, $file->getClientOriginalName());
    }
}
