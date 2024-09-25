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
    $user = auth()->user();//no le des bola si sale error parece que no reconoce el metodo
    $servicios = Servicio::get();
    $reservas = Reserva::get();
    $horarios = Horario::get();

    if ($user->hasRole('proveedor')) {
        return view('proveedor.dashboard', ['servicios' => $servicios, 'reservas' => $reservas, 'horarios' => $horarios]);
    } elseif ($user->hasRole('cliente')) {
        $reservas = Reserva::where('cliente_id', '=', $user->id)->get();
        return view('cliente.dashboard', ['servicios' => $servicios, 'reservas' => $reservas, 'horarios' => $horarios]);
    }return redirect()->route('home')->with('error', 'Unauthorized access');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/servicio/all', [ServicioController::class, 'index'])->name('servicio.index');

Route::get('/servicio/user', [ServicioController::class, 'userServices'])->name('servicio.userServices');


Route::get('/reservas/user', [ReservaController::class, 'reservaUsuario'])->name('reserva.user');

Route::post('servicio/reservas/{idservicio}', [ReservaController::class, 'reservaServicio'])->name('reserva.selected');

Route::resource('/reserva', ReservaController::class);
Route::resource('/servicio', ServicioController::class);
Route::resource('/horario', HorarioController::class);
Route::post('/horario/{id}', [HorarioController::class, 'returnSelected'])->name('horario.selected');

require __DIR__ . '/auth.php';
