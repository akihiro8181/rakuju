<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Actions\UpdateUser as ActionUpdateUser;

class UpdateUser extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, User $user)
    {
        app(ActionUpdateUser::class)->update($request->user(),$user , $request->all());

        return back(303)->with('flash', [
            'message' => "update User",
        ]);
    }
}
