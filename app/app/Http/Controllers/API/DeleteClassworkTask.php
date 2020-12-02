<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClassworkTask;
use App\Models\InCharge;
use App\Http\Controllers\Controller;
use App\Actions\DeleteClassworkTask as ActionDeleteClassworkTask;

class DeleteClassworkTask extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassworkTask  $classwork_task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, ClassworkTask $classwork_task)
    {
        app(ActionDeleteClassworkTask::class)->delete($request->user(), $classwork_task);

        return back(303)->with('flash', [
            'message' => "delete ClassworkTask",
        ]);
    }
}
