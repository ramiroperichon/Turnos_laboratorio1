<?php

namespace App\Http\Controllers;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Services\DashboardService;

class DashboardController extends Controller
{


    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function proveedor()
    {
        $user = auth()->user();
        $servicios = Servicio::where('proveedor_id', '=', $user->id)->get();
        $reservas = Reserva::whereHas('servicio', function ($query) use ($user) {
            $query->where('proveedor_id', '=', $user->id);
        })->get();

        $eventData = $this->dashboardService->mapEvents($reservas);


        return view('proveedor.dashboard', ['events' => $eventData, 'servicios' => $servicios, 'reservas' => $reservas]);
    }

    public function cliente()
    {
        $user = auth()->user();
        $reservas = Reserva::where('cliente_id', '=', $user->id)->take(6)->get();
        $servicios = Servicio::where('habilitado', '=', true)->get()->take(5);
        $servicioscount = Servicio::where('habilitado', '=', true)->count();
        $reservascount = Reserva::where('cliente_id', '=', $user->id)->count();
        return view('cliente.dashboard', ['servicios' => $servicios, 'reservas' => $reservas, 'servicioscount' => $servicioscount, 'reservascount' => $reservascount]);
    }

    public function administrador()
    {
        $reservas = Reserva::all();
        $servicios = Servicio::all();
        $eventData = $this->dashboardService->mapEvents($reservas);
        return view('administrador.dashboard', ['reservas' => $reservas, 'servicios' => $servicios, 'events' => $eventData]);
    }

    public function public(){
        return view('dashboard');
    }
}
