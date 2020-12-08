<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\ClassworkTask;

class ShowHomework extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ClassworkTask $classwork_task)
    {
        return Inertia::render('DataTest/HomeworkView', [
            'classwork_task' => ClassworkTask::find($classwork_task->id), 
            'storage' => Storage::allFiles(), 
            'file_name' => array_column($classwork_task->contents, 'file_name'),
        ]);
    }
}
