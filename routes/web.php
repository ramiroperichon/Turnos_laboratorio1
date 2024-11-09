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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', function () {
        $user = auth()->user(); //no le des bola si sale error parece que no reconoce el metodo
        $servicios = Servicio::get();
        $reservas = Reserva::get();

        $events = Reserva::all();

        $eventData = [];

        $eventColor = '#fc424a';

        foreach ($events as $event) {
            switch ($event->estado) {
                case 'Confirmado':
                    $eventColor = '#02a147';
                    break;
                case 'Pendiente':
                    $eventColor = '#00d25b';
                    break;
                case 'Cancelado':
                    $eventColor = '#fc424a';
                    break;
                default:
                    $eventColor = '#808080';
                    break;
            }

            if ($event->estado != 'Completado') {
                $eventData[] = [
                    'title' => $event->user->name . ' Turno en ' .
                        $event->servicio->nombre,
                    'start' => $event->fecha_reserva . ' ' . $event->hora_inicio,
                    'end' => $event->fecha_reserva . ' ' . $event->hora_fin,
                    'backgroundColor' => $eventColor,
                    'borderColor' => $eventColor
                ];
            }
        }


        if ($user->hasRole('proveedor')) {
            return view('proveedor.dashboard', ['events' => $eventData, 'servicios' => $servicios, 'reservas' => $reservas]);
        } elseif ($user->hasRole('cliente')) {
            $reservas = Reserva::where('cliente_id', '=', $user->id)->get();
            return view('cliente.dashboard', ['servicios' => $servicios, 'reservas' => $reservas]);
        } elseif ($user->hasRole('administrador')) {
            return view('administrador.dashboard');
        }
        return redirect()->route('home')->with('error', 'Unauthorized access');//devolver vista publica
    })->name('dashboard');//REFACTORIZAR ESTO ESTA HORRIBLE
});



Route::get('/servicio/all', [ServicioController::class, 'index'])->name('servicio.all');//Requiere rol proveedor
Route::get('/servicio/user', [ServicioController::class, 'userServices'])->name('servicio.userServices');//Require rol proveedor
Route::get('/reservas/user', [ReservaController::class, 'reservaUsuario'])->name('reserva.user');//Requiere rol cliente
Route::patch('/reserva/update/{reserva}/{estado}', [ReservaController::class, 'confirmReject'])->name('reserva.confirmreject');//Requiere rol proveedor o admin
Route::get('servicio/reservas/{idservicio}', [ReservaController::class, 'reservaServicio'])->name('reserva.selected');//Requiere rol proveedor o admin




Route::resource('/reserva', ReservaController::class);//revisar que metodos se utlizan para controlar los roles necesarios
Route::resource('/servicio', ServicioController::class);
Route::resource('/horario', HorarioController::class);



Route::post('/horario/{id}', [HorarioController::class, 'returnSelected'])->name('horario.selected');//requiere el rol cliente



//Todos tendrian que funcionar como este: controla el rol y redirecciona a la pagina forbidden si no tiene permiso
Route::group(['middleware' => ['role:administrador']], function () {
    Route::get('/administrador/reservas', [AdministradorController::class, 'reservas'])->name('administrador.reservas');
    Route::get('/administrador/servicios', [AdministradorController::class, 'servicios'])->name('administrador.servicios');
    Route::get('/administrador/detallesnegocio', [AdministradorController::class, 'detallesnegocio'])->name('administrador.detallenegocio');//llamar al detalle de negocio controller
    Route::put('/administrador/detallenegocio/{id}', [DetalleNegocioController::class, 'update'])->name('administrador.detallenegocio.update');
    Route::get('/administrador/proveedores', [AdministradorController::class, 'usuariosall'])->name('administrador.proveedores');
    Route::put('/administrador/modificarProveedor/{id}', [AdministradorController::class, 'modificarProveedor'])->name('administrador.modificarProveedor');
    Route::post('/administrador/crearProveedor', [AdministradorController::class, 'crearProveedor'])->name('administrador.crearProveedor');
    Route::get('/administrador/editarServicios/{id}', [AdministradorController::class, 'editarServicios'])->name('administrador.editarServicios');
});


require __DIR__ . '/auth.php';
