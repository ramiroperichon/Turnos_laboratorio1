<?php

use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\DetalleNegocioController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\DashboardController;
use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    $user = auth()->user();
    if (!$user) {
        return app(DashboardController::class)->public();
    }
    if ($user->hasRole('proveedor')) {
        return app(DashboardController::class)->proveedor();
    } elseif ($user->hasRole('cliente')) {
        return app(DashboardController::class)->cliente();
    } elseif ($user->hasRole('administrador')) {
        return app(DashboardController::class)->administrador();
    } else {
        return app(DashboardController::class)->public();
    }
})->name('dashboard');



Route::get('/servicio/all', [ServicioController::class, 'index'])->name('servicio.all'); //Requiere rol proveedor
Route::get('/servicio/user', [ServicioController::class, 'userServices'])->name('servicio.userServices'); //Require rol proveedor
Route::get('/servicio/crear', [ServicioController::class, 'create'])->name('servicio.create'); //Requiere rol proveedor
Route::post('/servicio/store', [ServicioController::class, 'store'])->name('servicio.store'); //Requiere rol proveedor
Route::patch('/servicio/update/{id}', [ServicioController::class, 'update'])->name('servicio.update'); //Requiere rol proveedor o admin
Route::delete('/servicio/destroy/{id}', [ServicioController::class, 'destroy'])->name('servicio.destroy'); //Requiere rol proveedor o admin
Route::patch('/reserva/update/{reserva}/{estado}', [ReservaController::class, 'confirmReject'])->name('reserva.confirmreject'); //Requiere rol proveedor o admin
Route::get('servicio/reservas/{idservicio}', [ReservaController::class, 'reservaServicio'])->name('reserva.selected'); //Requiere rol proveedor o admin
Route::get('/reservas/all', [ReservaController::class, 'index'])->name('reserva.index'); //Requiere rol proveedor


Route::get('/servicios/reservar/{id}', [ReservaController::class, 'create'])->name('reserva.create'); //requiere el rol cliente
Route::post('/servicios/reservar/store', [ReservaController::class, 'store'])->name('reserva.store'); //requiere el rol cliente
Route::patch('reservas/user/update/{id}', [ReservaController::class, 'cancelReserva'])->name('reserva.update'); //requiere el rol cliente
Route::get('/reservas/user', [ReservaController::class, 'reservaUsuario'])->name('reserva.user'); //Requiere rol cliente

//Todos tendrian que funcionar como este: controla el rol y redirecciona a la pagina forbidden si no tiene permiso
Route::group(['middleware' => ['role:administrador']], function () {
    Route::get('/administrador/reservas', [ReservaController::class, 'index'])->name('administrador.reservas');
    Route::get('/administrador/servicios', [AdministradorController::class, 'servicios'])->name('administrador.servicios');
    Route::get('/administrador/detallesnegocio', [AdministradorController::class, 'detallesnegocio'])->name('administrador.detallenegocio'); //llamar al detalle de negocio controller
    Route::put('/administrador/detallenegocio/{id}', [DetalleNegocioController::class, 'update'])->name('administrador.detallenegocio.update');
    Route::get('/administrador/proveedores', [AdministradorController::class, 'usuariosall'])->name('administrador.proveedores');
    Route::put('/administrador/modificarProveedor/{id}', [AdministradorController::class, 'modificarProveedor'])->name('administrador.modificarProveedor');
    Route::post('/administrador/crearProveedor', [AdministradorController::class, 'crearProveedor'])->name('administrador.crearProveedor');
    Route::get('/administrador/editarServicios/{id}', [AdministradorController::class, 'editarServicios'])->name('administrador.editarServicios');
});


require __DIR__ . '/auth.php';
