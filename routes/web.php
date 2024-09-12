<?php

use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function(){

    Route::prefix('/classrooms/trashed')
    ->as('classrooms.')
    ->controller(ClassroomsController::class)
    ->group(function(){

        Route::get('/','trashed')->name('trashed');
        Route::put('/{classroom}','restore')->name('restore');
        Route::delete('/{classroom}','forceDelete')->name('force-delete');
     });

     Route::get('/classrooms/{classroom}/join',[JoinClassroomController::class,'create'])->middleware('signed')->name('classrooms.join');

     Route::post('/classrooms/{classroom}/join',[JoinClassroomController::class,'store'])->name('classrooms.store');

    
    Route::resource('/classrooms',ClassroomsController::class)->names([
        // 'index'=>'classrooms/index'
    ])->where([
        'classroom'=>'\d+'
    ]);
   
});


