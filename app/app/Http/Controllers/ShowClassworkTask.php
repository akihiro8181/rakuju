<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\InCharge;

class ShowClassworkTask extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InCharge  $in_charge
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, InCharge $in_charge)
    {
        // ユーザー権限の認証
        Gate::forUser($request->user())->authorize('view', $in_charge);

        $in_charge->classwork;          // 授業情報を取得
        $in_charge->classwork_tasks;    // 課題情報を取得
        $in_charge->teacher;            // 課題情報を取得
        $in_charge->tutors;             // 課題情報を取得

        return Inertia::render('DataTest/ClassworkTaskView', [
            'in_charge' => $in_charge,  // 授業情報
        ]);
    }
}
