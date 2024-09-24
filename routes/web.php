<?php

use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ServicioController;
use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $servicios = Servicio::get();
    $reservas = Reserva::get();
    $horarios = Horario::get();
    return view('dashboard', ['servicios' => $servicios, 'reservas' => $reservas, 'horarios' => $horarios]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/reserva', ReservaController::class);
Route::resource('/servicio', ServicioController::class);
Route::post('/servicios/horarios/{id}', [HorarioController::class, 'returnSelected'])->name('horario.selected');

require __DIR__ . '/auth.php';
