<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ClassworkTask;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class DeleteClassworkTaskFile extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassworkTask  $classwork_task
     * @param  String  $file_name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, ClassworkTask $classwork_task, String $file_name)
    {
        $filePath = 'public/' . $classwork_task->id . '/' . $request->user()->id . '/' . $file_name;
        
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        return back(303)->with('flash', [
            'message' => "ファイルを削除しました",
        ]);
    }
}
