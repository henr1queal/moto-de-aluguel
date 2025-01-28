<?php

use App\Http\Controllers\FineController;
use App\Http\Controllers\MileageHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
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
    return view('start.page');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/locacoes/adicionar', [RentalController::class, 'create'])->middleware(['auth', 'verified'])->name('rental.new');
Route::get('/locacoes', [RentalController::class, 'index'])->middleware(['auth', 'verified'])->name('rental.index');
Route::get('/locacoes/{rental}', [RentalController::class, 'show'])->middleware(['auth', 'verified'])->name('rental.show');
Route::post('/locacoes', [RentalController::class, 'store'])->middleware(['auth', 'verified'])->name('rental.store');
Route::patch('/locacoes/{rental}', [RentalController::class, 'update'])->middleware(['auth', 'verified'])->name('rental.patch');

Route::view('/veiculos/adicionar', 'vehicle.new')->middleware(['auth', 'verified'])->name('vehicle.new');
Route::get('/veiculos', [VehicleController::class, 'index'])->middleware(['auth', 'verified'])->name('vehicle.index');
Route::post('/veiculos', [VehicleController::class, 'store'])->middleware(['auth', 'verified'])->name('vehicle.store');
Route::get('/veiculos/{vehicle}', [VehicleController::class, 'show'])->middleware(['auth', 'verified'])->name('vehicle.show');
Route::delete('/veiculos/{vehicle}', [VehicleController::class, 'destroy'])->middleware(['auth', 'verified'])->name('vehicle.delete');
Route::patch('/veiculos/{vehicle}', [VehicleController::class, 'update'])->middleware(['auth', 'verified'])->name('vehicle.update');

Route::get('/km-diaria/{vehicle}/{rental?}', [MileageHistoryController::class, 'index'])->middleware(['auth', 'verified'])->name('milleage.show');
Route::post('/km-diaria/{vehicle}', [MileageHistoryController::class, 'store'])->middleware(['auth', 'verified'])->name('milleage.store');
Route::delete('/km-diaria/{vehicle}', [MileageHistoryController::class, 'destroy'])->middleware(['auth', 'verified'])->name('milleage.delete');

Route::put('/multa/{fine}', [FineController::class, 'update'])->middleware(['auth', 'verified'])->name('fine.update');

// Depois as outras rotas
Route::post('/multa/{vehicle}', [FineController::class, 'store'])->middleware(['auth', 'verified'])->name('fine.store');
Route::delete('/multa/{vehicle}', [FineController::class, 'destroy'])->middleware(['auth', 'verified'])->name('fine.delete');
Route::get('/multa/{vehicle}/{rental?}', [FineController::class, 'index'])->middleware(['auth', 'verified'])->name('fine.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
