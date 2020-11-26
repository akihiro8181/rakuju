<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClassworkTask;
use App\Models\InCharge;
use App\Http\Controllers\Controller;
use App\Actions\UpdateClassworkTask as ActionUpdateClassworkTask;

class UpdateClassworkTask extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InCharge  $in_charge
     * @param  \App\Models\ClassworkTask  $in_charge
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, InCharge $in_charge, ClassworkTask $classwork_task)
    {
        app(ActionUpdateClassworkTask::class)->update($request->user(), $in_charge, $classwork_task, $request->all());

        return back(303)->with('flash', [
            'message' => "update ClassworkTask",
        ]);
    }
}
