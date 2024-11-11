<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Services\ServicioService;
use Illuminate\Http\Request;

class HorarioController extends Controller
{

    /*     protected $servicioService;

    public function __construct(ServicioService $servicioService)
    {
        $this->servicioService = $servicioService;
    }

    public function index()
    {
        $horarios = Horario::get();
        $servicio = Servicio::get();
        return view ('franjas.index', [
            'franjas' => $horarios, 'servicios' => $servicio,
            'selected' => $servicio->first()
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function returnSelected(int $id)
    {
        $selected = Servicio::firstWhere('id', $id);

        $reservas = Reserva::where('servicio_id', $id)->get();

        return view('horario.todos', ['servicio' => $selected, 'reservas' => $reservas ]);
    }
        */
}
