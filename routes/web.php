<?php

use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\DetalleNegocioController;
use App\Http\Controllers\AdministradorController;
use App\Mail\SolicitudMaileable;
use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    $user = auth()->user(); //no le des bola si sale error parece que no reconoce el metodo
    $servicios = Servicio::get();
    $reservas = Reserva::get();

    $events = Reserva::all();

    $eventData = [];

    $eventColor = '#FF0000'; // Default color (red)

    foreach ($events as $event) {

        switch ($event->estado) {
            case 'Confirmado':
                $eventColor = '#378006';
                break;
            case 'Pendiente':
                $eventColor = '#FFA500';
                break;
            case 'Cancelado':
                $eventColor = '#FF0000';
                break;
            default:
                $eventColor = '#808080';
                break;
        }

        $eventData[] = [
            'title' => $event->user->name . ' ' . $event->servicio->nombre,
            'start' => $event->fecha_reserva . ' ' . $event->hora_inicio,
            'end' => $event->fecha_reserva . ' ' . $event->hora_fin,
            'eventColor' => $eventColor
        ];
    }


    if ($user->hasRole('proveedor')) {
        return view('proveedor.dashboard', ['events' => $eventData, 'servicios' => $servicios, 'reservas' => $reservas]);
    } elseif ($user->hasRole('cliente')) {
        $reservas = Reserva::where('cliente_id', '=', $user->id)->get();
        return view('cliente.dashboard', ['servicios' => $servicios, 'reservas' => $reservas]);
    } elseif
    ($user->hasRole('administrador')) {
        return view('administrador.dashboard');

    }
    return redirect()->route('home')->with('error', 'Unauthorized access');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/servicio/all', [ServicioController::class, 'index'])->name('servicio.all');

Route::get('/servicio/user', [ServicioController::class, 'userServices'])->name('servicio.userServices');

Route::get('/reservas/user', [ReservaController::class, 'reservaUsuario'])->name('reserva.user');

Route::get('servicio/reservas/{idservicio}', [ReservaController::class, 'reservaServicio'])->name('reserva.selected');

Route::resource('/reserva', ReservaController::class);
Route::resource('/servicio', ServicioController::class);
Route::resource('/horario', HorarioController::class);
Route::post('/horario/{id}', [HorarioController::class, 'returnSelected'])->name('horario.selected');
Route::resource('/detalleNegocio', DetalleNegocioController::class);


Route::get('/administrador/reservas', [AdministradorController::class, 'reservas'])->name('administrador.reservas');
Route::get('/administrador/servicios', [AdministradorController::class, 'servicios'])->name('administrador.servicios');
Route::get('/administrador/detallesnegocio', [AdministradorController::class, 'detallesnegocio'])->name('administrador.detallenegocio');
Route::get('/administrador/usuarios', [AdministradorController::class, 'usuariosall'])->name('administrador.usuarios');

require __DIR__ . '/auth.php';
