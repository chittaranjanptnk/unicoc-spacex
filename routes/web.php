<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaunchController;

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

Route::get('/', [LaunchController::class, 'index'])->name('launches');
Route::get('/launches/{flightNumber}', [LaunchController::class, 'show'])->name('launches-show')->where('flightNumber', '[0-9]+');
