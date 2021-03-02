<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\LabelController;

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

Auth::routes();

Route::resource('/', HomeController::class)->only('index');
Route::resources(
    [
        'task_statuses' => TaskStatusController::class,
        'labels' => LabelController::class,
        'tasks' => TaskController::class
    ],
);
