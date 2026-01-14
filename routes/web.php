<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hall/{id}', [BookingController::class, 'show'])->name('hall.show');
Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
