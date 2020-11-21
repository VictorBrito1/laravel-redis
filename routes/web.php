<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\DefaultController::class, 'index']);

Route::group(['prefix' => '/users'], function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::get('/edit/{cpf}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/delete/{cpf}', [UserController::class, 'delete'])->name('user.delete');
});
