<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::post('/task/add', [App\Http\Controllers\HomeController::class, 'index'])->name('task.add');

Route::controller(TaskController::class)->group(function () {
    Route::post('/tasks', 'store')->name('task.add');
    Route::post('/tasks/update', 'update')->name('task.update');
    Route::post('/tasks/addImage', 'addImage')->name('task.addImage');
    Route::post('/tasks/deleteImage', 'deleteImage')->name('task.deleteImage');
    Route::post('/tasks/destroy', 'destroy')->name('task.destroy');
});

Route::controller(TagController::class)->group(function () {
    Route::post('/tags', 'store')->name('tag.add');
    Route::post('/tags/update', 'update')->name('tag.update');
    Route::post('/tags/filterTags', 'filterTags')->name('tag.filterTags');
    Route::post('/tags/destroy', 'destroy')->name('tag.destroy');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
