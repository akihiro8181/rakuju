<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CreateClassworkTask;
use App\Http\Controllers\API\UpdateClassworkTask;
use App\Http\Controllers\API\DeleteClassworkTask;
use App\Http\Controllers\API\UpdateSchoolInformation;
use App\Http\Controllers\API\UploadHomework;
use App\Http\Controllers\API\DownloadClassworkTaskFile;
use App\Http\Controllers\API\DeleteClassworkTaskFile;
use App\Http\Controllers\API\AddUser;
use App\Http\Controllers\API\UpdateUser;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/classwork-task/{in_charge}', CreateClassworkTask::class)->where('in_charge', '[0-9]+');
Route::middleware('auth:sanctum')->put('/classwork-task/{classwork_task}', UpdateClassworkTask::class)->where('classwork_task', '[0-9]+');
Route::middleware('auth:sanctum')->delete('/classwork-task/{classwork_task}', DeleteClassworkTask::class)->where('classwork_task', '[0-9]+');

Route::middleware('auth:sanctum')->put('/schoolinformation/{school}', UpdateSchoolInformation::class)->where('school', '[0-9]+');
Route::middleware('auth:sanctum')->post('/homework/{classwork_task}', UploadHomework::class)->where('classwork_task', '[0-9]+');
Route::middleware('auth:sanctum')->get('/storage/{classwork_task}/{file_name}', DownloadClassworkTaskFile::class)->where('classwork_task', '[0-9]+');
Route::middleware('auth:sanctum')->delete('/storage/{classwork_task}/{file_name}', DeleteClassworkTaskFile::class)->where('classwork_task', '[0-9]+');

// manageUser関連
Route::middleware('auth:sanctum')->post('/add-user/', AddUser::class);
Route::middleware('auth:sanctum')->put('/add-user/{user}', UpdateUser::class)->where('user', '[0-9]+');