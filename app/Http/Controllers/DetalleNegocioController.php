<?php

namespace App\Http\Controllers;

use App\Models\DetalleNegocio;
use Illuminate\Http\Request;

class DetalleNegocioController extends Controller
{

    public function index()
    {

        $detalleNegocio = DetalleNegocio::first();

        return view('negocio.create', data: [
            'old' => $detalleNegocio
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('negocio.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleNegocio $detalleNegocio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {

        $detalleNegocio = DetalleNegocio::first();

        return view('negocio.edit', data: [
            'old' => $detalleNegocio
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleNegocio $detalleNegocio)
    {

        $detalleNegocioActualizado = DetalleNegocio::firstWhere('id', $detalleNegocio->id);

        $validated = $request->validate(
            [
                'nombre' => 'required|min:3|max:30',
                'email' => 'required|email|max:50',
                'telefono' => 'required|numeric',
                'latitud' => 'required|numeric',
                'logitud' => 'required|numeric',
                'Iurl' => 'required|url',
                'Furl' => 'required|url',
                'Turl' => 'required|url',
                'Xurl' => 'required|url',
            ]
        );

        $detalleNegocioActualizado->update($validated);
        return redirect('/')->with('status', 'se actualizo correctamente los datos del negocio!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleNegocio $detalleNegocio)
    {
        //
    }
}
