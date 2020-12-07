<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ShowDashboard;
use \App\Http\Controllers\ShowClassworkTask;
use \App\Http\Controllers\ShowSchoolInformation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', ShowDashboard::class)->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/schoolinformation', ShowSchoolInformation::class)->name('schoolinformation');


Route::middleware(['auth:sanctum', 'verified'])->get('/classwork-task/{in_charge}', ShowClassworkTask::class)->name('classwork-task')->where('in_charge', '[0-9]+');

Route::middleware(['auth:sanctum', 'verified'])->get('/datatest', function () {
    return Inertia\Inertia::render('DataTest/DataTest');
})->name('datatest');

Route::middleware(['auth:sanctum', 'verified'])->get('/student', function () {
    return Inertia\Inertia::render('DataTest/Student');
})->name('student');

Route::middleware(['auth:sanctum', 'verified'])->get('/teacher', function () {
    return Inertia\Inertia::render('DataTest/Teacher');
})->name('teacher');

Route::middleware(['auth:sanctum', 'verified'])->get('/admin', function () {
    return Inertia\Inertia::render('DataTest/Admin');
})->name('admin');

//学生のvideoページのルート
Route::middleware(['auth:sanctum', 'verified'])->get('/studentVideo', function () {
    return Inertia\Inertia::render('DataTest/StudentVideo');
})->name('studentVideo');

//先生のvideoページのルート
Route::middleware(['auth:sanctum', 'verified'])->get('/teacherVideo', function () {
    return Inertia\Inertia::render('DataTest/TeacherVideo');
})->name('teacherVideo');