<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Actions\DeleteUser as ActionDeleteUser;

class DeleteUser extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request , User $user)
    {
        app(ActionDeleteUser::class)->delete($request->user(), $user , $request->all());

        return back(303)->with('flash', [
            'message' => "Delete User",
        ]);
    }
}
