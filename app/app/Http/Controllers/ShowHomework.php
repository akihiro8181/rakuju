<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\ClassworkTask;
use App\Models\Classwork;
use Illuminate\Support\Str;

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
        // ファイルの名前だけを取り出す
        // ファイル名の配列を一旦コレクションに変換して、map()でそれぞれのディレクトリ部分を削除
        $files = collect(Storage::allFiles('public/' . $classwork_task->id . '/' . $request->user()->id))->map(
            function($name) {
                $name = Str::after($name, '/'); // {classwork_task->id}/{user->id}/file_name
                $name = Str::after($name, '/'); // {user->id}/file_name
                $name = Str::after($name, '/'); // file_name
                return $name;
            });

        return Inertia::render('DataTest/HomeworkView', [
            'classwork_name' => ClassworkTask::find($classwork_task->id)->in_charge->classwork->name,
            'classwork_task' => ClassworkTask::find($classwork_task->id), 
            'files' => $files, 
            'file_name' => array_column($classwork_task->contents, 'file_name'),
        ]);
    }
}
