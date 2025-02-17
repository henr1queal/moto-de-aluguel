<?php

use App\Http\Controllers\FineController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MileageHistoryController;
use App\Http\Controllers\OilChangeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Artisan;
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
})->middleware(['auth'])->name('home');

Route::get('/locacoes/adicionar', [RentalController::class, 'create'])->middleware(['auth'])->name('rental.new');
Route::get('/locacoes', [RentalController::class, 'index'])->middleware(['auth'])->name('rental.index');
Route::get('/locacoes/{rental}', [RentalController::class, 'show'])->middleware(['auth'])->name('rental.show');
Route::post('/locacoes', [RentalController::class, 'store'])->middleware(['auth'])->name('rental.store');
Route::patch('/locacoes/{rental}', [RentalController::class, 'update'])->middleware(['auth'])->name('rental.patch');
Route::delete('/rental/photo/{rental}', [RentalController::class, 'deletePhoto'])->middleware('auth')->name('photo.delete');
Route::get('/rental/photo/{rental}', [RentalController::class, 'photo'])->middleware('auth')->name('photo.show');
Route::post('/locacoes/{rental}/finalizar', [RentalController::class, 'finish'])->name('rentals.finish');


Route::get('/notificacoes', [ReminderController::class, 'getNotifications'])->middleware('auth')->name('notifications');
Route::get('/notificacoes/contagem', [ReminderController::class, 'getNotificationsCount'])->middleware('auth');

Route::view('/veiculos/adicionar', 'vehicle.new')->middleware(['auth'])->name('vehicle.new');
Route::get('/veiculos', [VehicleController::class, 'index'])->middleware(['auth'])->name('vehicle.index');
Route::post('/veiculos', [VehicleController::class, 'store'])->middleware(['auth'])->name('vehicle.store');
Route::get('/veiculos/{vehicle}', [VehicleController::class, 'show'])->middleware(['auth'])->name('vehicle.show');
Route::delete('/veiculos/{vehicle}', [VehicleController::class, 'destroy'])->middleware(['auth'])->name('vehicle.delete');
Route::patch('/veiculos/{vehicle}', [VehicleController::class, 'update'])->middleware(['auth'])->name('vehicle.update');

Route::get('/km-diaria/{vehicle}/{rental?}', [MileageHistoryController::class, 'index'])->middleware(['auth'])->name('milleage.show');
Route::post('/km-diaria/{vehicle}', [MileageHistoryController::class, 'store'])->middleware(['auth'])->name('milleage.store');
Route::delete('/km-diaria/{vehicle}', [MileageHistoryController::class, 'destroy'])->middleware(['auth'])->name('milleage.delete');
Route::patch('/km-diaria/{id}', [MileageHistoryController::class, 'updateObservation']);


Route::get('/manutencao/{vehicle}/{rental?}', [MaintenanceController::class, 'index'])->middleware(['auth'])->name('maintenance.show');
Route::post('/manutencao/{vehicle}', [MaintenanceController::class, 'store'])->middleware(['auth'])->name('maintenance.store');
Route::delete('/manutencao/{vehicle}', [MaintenanceController::class, 'destroy'])->middleware(['auth'])->name('maintenance.delete');

Route::get('/troca-de-oleo/{vehicle}/{rental?}', [OilChangeController::class, 'index'])->middleware(['auth'])->name('oil-change.show');
Route::post('/troca-de-oleo/{vehicle}', [OilChangeController::class, 'store'])->middleware(['auth'])->name('oil-change.store');
Route::delete('/troca-de-oleo/{vehicle}', [OilChangeController::class, 'destroy'])->middleware(['auth'])->name('oil-change.delete');

Route::put('/multa/{fine}', [FineController::class, 'update'])->middleware(['auth'])->name('fine.update');
Route::post('/multa/{vehicle}', [FineController::class, 'store'])->middleware(['auth'])->name('fine.store');
Route::delete('/multa/{vehicle}', [FineController::class, 'destroy'])->middleware(['auth'])->name('fine.delete');
Route::get('/multa/{vehicle}/{rental?}', [FineController::class, 'index'])->middleware(['auth'])->name('fine.show');

Route::get('/financas/totais', [PaymentController::class, 'getTotals'])
    ->middleware(['auth'])
    ->name('payment.totals');
    Route::get('/financas/semanas', [PaymentController::class, 'getWeeks'])->name('payment.weeks');

Route::get('/financas/{vehicle}/{rental?}', [PaymentController::class, 'show'])->middleware(['auth'])->name('payment.show');
Route::put('/financas/{payment}', [PaymentController::class, 'update'])->middleware(['auth'])->name('payment.update');
Route::get('/financas', [PaymentController::class, 'index'])->name('payment.index'); // Página inicial

Route::get('/usuarios', [UserController::class, 'index'])->name('user.index')->middleware(['auth']);
Route::post('/usuarios/adicionar', [UserController::class, 'store'])->name('user.store')->middleware(['auth']);
Route::post('/usuarios/{user}/gerar-nova-senha', [UserController::class, 'generateNewPassword'])->name('user.new-password')->middleware(['auth']);
Route::post('/usuarios/{user}/mudar-tipo', [UserController::class, 'toggleRole'])->name('user.toggle-role')->middleware(['auth']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return response()->json(['message' => 'Cache limpo com sucesso!']);
});

require __DIR__ . '/auth.php';
