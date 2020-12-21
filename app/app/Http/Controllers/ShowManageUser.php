<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\School;
use App\Models\User;

class ShowManageUser extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $school_id = $user->school->id;

        $users = User::where('school_id','=',$school_id)->get();

        // $school_id = $user->school->id;
        // dd($user);
        // dd($school_id);
        // return Inertia::render('DataTest/ManageUser', ['users' => User::all() ] );
        return Inertia::render('DataTest/ManageUser', [
            'users'=> $users,
        ]);

    }
}
