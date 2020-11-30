<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClassworkTask;
use App\Models\InCharge;
use App\Http\Controllers\Controller;
use App\Actions\CreateNewClassworkTask;

class CreateClassworkTask extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InCharge  $in_charge
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, InCharge $in_charge)
    {
        app(CreateNewClassworkTask::class)->create($request->user(), $in_charge, $request->all());

        return back(303)->with('flash', [
            'message' => "create ClassworkTask",
        ]);
    }
}
