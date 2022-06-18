<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\HomeAController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Admin\UserController;
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

Route::get('/', [HomeController::class, 'index']);


Route::prefix('painel')->group(function(){
    Route::get('/',[HomeAController::class, 'index'])->name('admin');

    Route::get('login',[AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',[AuthenticatedSessionController::class, 'store']);

    Route::get('register',[RegisteredUserController::class, 'create'])->name('register');
    Route::post('register',[RegisteredUserController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::resource('users', UserController::class);

});

