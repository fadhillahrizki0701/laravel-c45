<?php

use App\Http\Controllers\Dataset1Controller;
use App\Http\Controllers\Dataset2Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Datatrain1Controller;
use App\Http\Controllers\Datatrain2Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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
Route::get('/', function (){
return redirect('/login');

});

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::middleware("auth")->group(function(){
    Route::resource('/dataset1', Dataset1Controller::class);
    Route::resource('/dataset2', Dataset2Controller::class);
    Route::resource('/dashboard', DashboardController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/datatrain1', Datatrain1Controller::class);
    Route::delete('/datatrain1/clear/all', [Datatrain1Controller::class, 'clear'])->name('datatrain1.clear');
    Route::delete('/datatrain2/clear/all', [Datatrain2Controller::class, 'clear'])->name('datatrain2.clear');
    Route::resource('/datatrain2', Datatrain2Controller::class);
    Route::get('/mining',[Dataset1Controller::class, 'mining']);
});