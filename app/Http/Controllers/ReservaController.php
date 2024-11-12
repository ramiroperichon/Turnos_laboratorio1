<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Servicio;
use App\Services\ServicioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Masmerise\Toaster\Toaster;

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
        return view('reserva.todas');
    }

    public function reservaUsuario()
    {
        $user = auth()->user();
        $reservas = Reserva::where('cliente_id', $user->id)->orderBy('fecha_reserva', 'desc')->get();
        return view('cliente.reservas', [
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
    public function create(int $id)
    {
        $selected = Servicio::firstWhere('id', $id);

        $reservas = Reserva::where('servicio_id', $id)->get();

        return view('reserva.create', ['servicio' => $selected, 'reservas' => $reservas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('horario_id')) {
            $horarioId = explode(',', $request->input('horario_id'));
        } else {
            Toaster::error('Debe seleccionar un horario!');
            return redirect()->back()->withInput();
        }
        $horarioId = explode(',', $request->input('horario_id'));

        $validator = Validator::make($request->all(), [
            'servicio_id' => 'required|integer',
            'fecha_reserva' => 'required|date|after_or_equal:today',
            'cliente_id' => 'required|integer',
            'horario_id' => 'required',
        ], [
            'fecha_reserva.after_or_equal' => 'La hora de fin no puede ser menor a la de inicio!',
        ]);
        $reservina = Reserva::where('hora_inicio', '=', $horarioId[0])
            ->where('hora_fin', '=', $horarioId[1])
            ->where('servicio_id', '=', $request->input('servicio_id'))
            ->where('fecha_reserva', '=', $request->input('fecha_reserva'))
            ->get();
        if ($reservina->count() > 0) {
            Toaster::error('Ya existe una reserva con el mismo horario');
            return redirect()->route('reserva.create', $request->input('servicio_id'));
        }
        if ($validator->fails()) {
            Toaster::error('Error al crear la reserva' . $validator->errors());
            return redirect()->route('reserva.create', $request->input('servicio_id'));
        }
        try {
            Reserva::create([
                'servicio_id' => $request->input('servicio_id'),
                'fecha_reserva' => $request->input('fecha_reserva'),
                'cliente_id' => $request->input('cliente_id'),
                'hora_inicio' => $horarioId[0],
                'hora_fin' => $horarioId[1]
            ]);
            Toaster::success('Se creo correctamente la reserva');
            return redirect()->route('servicios.index');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Error al crear la reserva');
        }
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

    public function cancelReserva(int $id)
    {
        try {
            $reservaN = Reserva::firstWhere('id', $id);
            if ($reservaN->estado != 'Pendiente') {
                Toaster::error('No se puede cambiar el estado de la reserva');
                return redirect()->route('reserva.user');
            }
            $this->servicioService->UpdateReserva($reservaN, 'Cancelado');
            Toaster::warning('Se cancelo la reserva');
            return redirect()->route('reserva.user');
        } catch (Exception $e) {
            Toaster::error('Error al actualizar la reserva' . $e->getMessage());
            return redirect()->route('reserva.user');
        }
    }

    public function confirmReject($reserva, $estado)
    {
        try {
            $reservaN = Reserva::firstWhere('id', $reserva);
            if ($reservaN->estado != 'Pendiente') {
                Toaster::error('No se puede cambiar el estado de la reserva');
                return redirect()->route('dashboard');
            }
            $this->servicioService->UpdateReserva($reservaN, $estado);
            Toaster::success('Se actualizo correctamente la reserva');
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Toaster::error('Error al actualizar la reserva' . $e->getMessage());
            return redirect()->route('dashboard');
        }
    }


    public function destroy(Reserva $reserva)
    {
        //$this->servicioService->updateFranjaStateOnDelete($reserva->horario_id);
        try {
            $reserva->delete();
            Toaster::success('Se borro correctamente la reserva');
            return redirect()->route('reserva.index');
        } catch (Exception $e) {
            Toaster::error('Error al borrar la reserva');
            return redirect()->route('reserva.index');
        }
    }
}
