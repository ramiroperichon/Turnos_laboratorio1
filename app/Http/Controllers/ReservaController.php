<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Services\ServicioService;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Masmerise\Toaster\Toast;

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
        return view('reserva.todas', [
            'reservas' => $reservas
        ]);
    }

    public function reservaUsuario()
    {
        $user = auth()->user();
        $reservas = Reserva::where('cliente_id', $user->id)->get();
        return view('cliente.todas', [
            'reservas' => $reservas
        ]);
    }

    public function reservaServicio(int $idservicio)
    {
        return view('reserva.selected', [
            'idServicio' => $idservicio
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
        $horarioId = explode(',', $request->input('horario_id'));

        $validator = Validator::make($request->all(), [
            'servicio_id' => 'required|integer',
            'fecha_reserva' => 'required|date|after_or_equal:today',
            'cliente_id' => 'required|integer',
            'horario_id' => 'required',
        ], [
            'fecha_reserva.after_or_equal' => 'La hora de fin no puede ser menor a la de inicio!',
        ]);

        if ($validator->fails()) {
            return redirect('/servicio')->withErrors($validator)->withInput();
        }

        try {
            Reserva::create([
                'servicio_id' => $request->input('servicio_id'),
                'fecha_reserva' => $request->input('fecha_reserva'),
                'cliente_id' => $request->input('cliente_id'),
                'hora_inicio' => $horarioId[0],
                'hora_fin' => $horarioId[1]
            ]);
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Error al crear la reserva');
        }

        // Update the horario state
        //$this->servicioService->updateHorarioState($validated['horario_id']);

        Toast::success('Se creo correctamente la reserva');
        return redirect()->route('dashboard');
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
        try {
            $reservaN = Reserva::firstWhere('id', $reserva->id);

            $this->servicioService->UpdateReserva($reservaN, $request->estado);

            Toast::success('Se actualizo correctamente la reserva');
            return redirect()->route('reserva.index');
        } catch (Exception $e) {
            Toast::error('Error al actualizar la reserva' . $e->getMessage());
            return redirect()->route('reserva.index');
        }
    }

    public function confirmReject($reserva, $estado)
    {
        try {
            $reservaN = Reserva::firstWhere('id', $reserva);
            if ($reservaN->estado != 'Pendiente') {
                Toast::error('No se puede cambiar el estado de la reserva');
                return redirect()->route('dashboard');
            }
            $this->servicioService->UpdateReserva($reservaN, $estado);
            Toast::success('Se actualizo correctamente la reserva');
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Toast::error('Error al actualizar la reserva' . $e->getMessage());
            return redirect()->route('dashboard');
        }
    }


    public function destroy(Reserva $reserva)
    {
        //$this->servicioService->updateFranjaStateOnDelete($reserva->horario_id);
        try {
            $reserva->delete();
            Toast::success('Se borro correctamente la reserva');
            return redirect()->route('reserva.index');
        } catch (Exception $e) {
            Toast::error('Error al borrar la reserva');
            return redirect()->route('reserva.index');
        }
    }
}
