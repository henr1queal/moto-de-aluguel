<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
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

Route::get('/home', function () {
    return view('start.page');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/locacoes', function () {
    return view('rental.home');
})->middleware(['auth', 'verified'])->name('rental-home');

Route::view('/locacoes/adicionar', 'rental.new')->middleware(['auth', 'verified'])->name('rental-new');

Route::view('/veiculos/adicionar', 'vehicle.new')->middleware(['auth', 'verified'])->name('vehicle-new');
Route::get('/veiculos', [VehicleController::class, 'index'])->middleware(['auth', 'verified'])->name('vehicle-home');
Route::get('/veiculos/{vehicle}', [VehicleController::class, 'show'])->middleware(['auth', 'verified'])->name('vehicle-show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
