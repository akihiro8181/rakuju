<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\ClassworkTask;
use App\Models\Classwork;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        // ファイルの一覧
        $files = Storage::allFiles('public/' . $classwork_task->id . '/' . $request->user()->id);

        // ファイルの名前だけを取り出す
        // ファイル名の配列を一旦コレクションに変換して、map()でそれぞれのディレクトリ部分を削除
        $file_names = collect($files)->map(
            function($name) {
                $name = Str::after($name, '/'); // {classwork_task->id}/{user->id}/file_name
                $name = Str::after($name, '/'); // {user->id}/file_name
                $name = Str::after($name, '/'); // file_name
                return $name;
            });
            
        // 最後にファイルがアップロードされた日を取得;
        $last_upload_date = null;   // 最終更新日
        
        foreach ($files as $file_name) {
            // 先に存在チェックをしないとエラーを吐く
            if (Storage::exists($file_name)) {
                $time = Storage::lastModified($file_name);
                if ($time > $last_upload_date) {
                    $last_upload_date = $time;
                }
            }
        }

        // 日付が取得できていればUNIXタイムスタンプから文字列に変換
        if ($last_upload_date != null) {
            $last_upload_date = new Carbon($last_upload_date);
            $last_upload_date->setTimezone('Asia/Tokyo');
        }

        // 提出期限日を取得
        $deadline = null;
        foreach (collect(ClassworkTask::find($classwork_task->id)->contents) as $content) {
            if ($content['type'] == 'homework') {
                if (array_key_exists('deadline', $content)) {
                    $deadline = $content['deadline'];
                    break;
                }
            }
        }

        return Inertia::render('DataTest/HomeworkView', [
            'classwork_name' => ClassworkTask::find($classwork_task->id)->in_charge->classwork->name,
            'classwork_task' => ClassworkTask::find($classwork_task->id), 
            'files' => $file_names, 
            'file_name' => array_column($classwork_task->contents, 'file_name'),
            'last_upload_date' => $last_upload_date->format('Y-m-d H:i'),
            'deadline' => $deadline,
        ]);
    }
}
