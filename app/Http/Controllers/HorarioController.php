<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Servicio;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::get();
        $servicio = Servicio::get();
        return view ('franjas.index', [
            'franjas' => $horarios, 'servicios' => $servicio,
            'selected' => $servicio->first()
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function returnSelected(int $id)
    {

        // Fetch time slots for the selected service
        $horarios = Horario::where('servicio_id', $id)->get();

        // Fetch all services
        $selected = Servicio::firstWhere('id', $id);

        return view('horario.todos', ['horarios' => $horarios, 'servicio' => $selected ]);
    }
}
