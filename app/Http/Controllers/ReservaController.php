<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Services\ServicioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ReservaController extends Controller
{
    protected $servicioService;

    public function __construct(ServicioService $servicioService)
    {
        $this->servicioService = $servicioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::get();
        return view ('reserva.todas', [
            'reservas' => $reservas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
                'servicio_id' => 'required|integer',
                'horario_id' => 'required|integer',
                'fecha_reserva' => 'required|date|after_or_equal:today',
                'cliente_id' => 'required|integer',
            ], [
                'fecha_reserva.after_or_equal' => 'La hora de fin no puede ser menor a la de inicio!',
            ]);

            // Create the Reserva after validation passes
            Reserva::create([
                'servicio_id' => $validated['servicio_id'],
                'horario_id' => $validated['horario_id'],
                'fecha_reserva' => $validated['fecha_reserva'],
                'cliente_id' => $validated['cliente_id']
            ]);

            // Update the horario state
            $this->servicioService->updateHorarioState($validated['horario_id']);

            // Redirect to the home page with success message
            return redirect()->route('servicio.index')->with('status', 'Se creo la reserva correctamente!');


        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $reservaN = Reserva::firstWhere('id', $reserva->id);

        $reservaN->update([
            'estado' => $request->estado
        ]);
        return redirect()->route('reserva.index')->with('status', 'Reserva se actualizo correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        $this->servicioService->updateFranjaStateOnDelete($reserva->horario_id);
        return redirect()->route('reserva.index')->with('status', 'Reserva borrada correctamente!');
    }
}
