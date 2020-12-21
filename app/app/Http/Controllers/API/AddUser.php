<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Actions\AddUser as ActionAddUser;

class AddUser extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        app(ActionAddUser::class)->add($request->user(), $request->all());

        return back(303)->with('flash', [
            'message' => "Add User",
        ]);
    }
}
