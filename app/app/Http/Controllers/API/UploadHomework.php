<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClassworkTask;
use App\Http\Controllers\Controller;
use App\Actions\UploadHomework as ActionUploadHomework;

class UploadHomework extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InCharge  $in_charge
     * @param  \App\Models\ClassworkTask  $in_charge
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, ClassworkTask $classwork_task)
    {
        $files = $request->file("files");

        // リクエスト内のファイルを一つずつ取り出して保存処理を行う
        foreach ($files as $file) {
            app(ActionUploadHomework::class)->upload($request->user(), $classwork_task, $file);
        }
        
        return back(303)->with('flash', [
            'message' => "upload Homework",
        ]);
    }
}
