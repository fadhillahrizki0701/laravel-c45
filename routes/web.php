<?php

use App\Http\Controllers\C45\C45Controller;
use App\Http\Controllers\Dataset1Controller;
use App\Http\Controllers\Dataset2Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Datatrain1Controller;
use App\Http\Controllers\Datatrain2Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
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
Route::get('/', function () {
    return redirect('/login');
});

// User Authentication
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware("auth")->group(function () {
    Route::resource('/dataset1', Dataset1Controller::class);
    Route::resource('/dataset2', Dataset2Controller::class);
    Route::resource('/dashboard', DashboardController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/datatrain1', Datatrain1Controller::class);
    Route::delete('/datatrain1/clear/all', [Datatrain1Controller::class, 'clear'])->name('datatrain1.clear');
    Route::delete('/datatrain2/clear/all', [Datatrain2Controller::class, 'clear'])->name('datatrain2.clear');
    Route::resource('/datatrain2', Datatrain2Controller::class);
    Route::get('/mining',[Dataset1Controller::class, 'mining']);

    Route::get('/profile/{id:id}', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/{id:id}', [ProfileController::class, 'update'])->name('profile.update');
    
    // test
    Route::get('test', [TestController::class, 'index']);
});

Route::get('/mining-dataset-1', [C45Controller::class, 'fetchTreeDataset1'])->name('proses-mining-dataset-1');
Route::get('/mining-dataset-2', [C45Controller::class, 'fetchTreeDataset2'])->name('proses-mining-dataset-2');