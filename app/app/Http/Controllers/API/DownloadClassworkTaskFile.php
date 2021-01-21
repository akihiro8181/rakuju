<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ClassworkTask;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class DownloadClassworkTaskFile extends Controller
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
        $mimeType = Storage::mimeType($filePath);   // ファイルの種類を取得
        $headers = [['Content-Type' => $mimeType]];
        // 'attachment'にするとDLリンクに、'inline'(規定値)にするとプレビュー画面に飛ぶ
        $disposition = 'inline';

        if (Storage::exists($filePath)) {
            return Storage::response($filePath, $file_name, $headers, $disposition);
        } else {
            return back(303)->with('flash', [
                'message' => "ファイルが存在しません。",
            ]);
        }
    }
}
