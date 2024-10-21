<?php

use App\Http\Controllers\C45\C45Controller;
use App\Http\Controllers\Dataset1Controller;
use App\Http\Controllers\Dataset2Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatasetFileUpload1Controller;
use App\Http\Controllers\DatasetFileUpload2Controller;
use App\Http\Controllers\Datatest1Controller;
use App\Http\Controllers\Datatest2Controller;
use App\Http\Controllers\Datatrain1Controller;
use App\Http\Controllers\Datatrain2Controller;
use App\Http\Controllers\Klasifikasi1Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
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
    return redirect()->route('login');
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
    Route::resource('/datatrain2', Datatrain2Controller::class);

    Route::post('/dataset-file-upload-1', [DatasetFileUpload1Controller::class, 'store'])->name('dataset-file-upload-1.store');
    Route::delete('/dataset-file-upload-1/clear', [DatasetFileUpload1Controller::class, 'clear'])->name('dataset-file-upload-1.clear');

    Route::post('/dataset-file-upload-2', [DatasetFileUpload2Controller::class, 'store'])->name('dataset-file-upload-2.store');
    Route::delete('/dataset-file-upload-2/clear', [DatasetFileUpload1Controller::class, 'clear'])->name('dataset-file-upload-2.clear');

    Route::get('/profile/{id:id}', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/{id:id}', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('/datatest1', Datatest1Controller::class);
    Route::resource('/datatest2', Datatest2Controller::class);

    Route::resource('/klasifikasi1', Klasifikasi1Controller::class);
    Route::resource('/klasifikasi2', Datatest2Controller::class);

});

Route::get('/mining-dataset-1', [C45Controller::class, 'fetchTreeDataset1'])->name('proses-mining-dataset-1');
Route::get('/mining-dataset-2', [C45Controller::class, 'fetchTreeDataset2'])->name('proses-mining-dataset-2');
