<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaunchController;
use App\Http\Controllers\GoogleDiveController;

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

Route::get('/', [LaunchController::class, 'index'])->name('home-launches');
Route::get('/launches', [LaunchController::class, 'index'])->name('launches');
Route::get('/launches/{flightNumber}', [LaunchController::class, 'show'])->name('launches-show')->where('flightNumber', '[0-9]+');

Route::get('/google-drive/set-token/{code?}', [GoogleDiveController::class, 'setToken'])->name('google-drive-set-token');
Route::get('/google-drive/upload-token', [GoogleDiveController::class, 'uploadToken'])->name('google-drive-upload-token');