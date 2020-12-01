<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CreateClassworkTask;
use App\Http\Controllers\API\UpdateClassworkTask;
use App\Http\Controllers\API\DeleteClassworkTask;

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
