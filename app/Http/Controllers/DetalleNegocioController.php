<?php

namespace App\Http\Controllers;

use App\Models\DetalleNegocio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Masmerise\Toaster\Toaster;

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleNegocio $detalleNegocio)
    {
        $detalleNegocioActualizado = DetalleNegocio::first();

        $validated = $request->validate(
            [
                'nombre' => 'required|min:3|max:30',
                'email' => 'required|email|max:50',
                'telefono' => 'required|numeric',
                'Iurl' => 'nullable|url',
                'Furl' => 'nullable|url',
                'Turl' => 'nullable|url',
                'Xurl' => 'nullable|url',
            ]
        );

        try{
        $detalleNegocioActualizado->update($validated);
        View::share('detallenegocioProviders', $detalleNegocioActualizado);
        Toaster::success('Se actualizo correctamente los datos del negocio!');
        return redirect()->route('administrador.detallenegocio', [$detalleNegocioActualizado->id]);
        }
        catch (Exception $e){
            Toaster::error('Error al actualizar los datos del negocio' . $e->getMessage());
            return redirect()->back()->withInput();
        }

    }


    public function updateUbicacion(Request $request, DetalleNegocio $detalleNegocio)
    {
        $detalleNegocioActualizado = DetalleNegocio::first();

        $validated = $request->validate(
            [
                'direccion' => 'required|min:3|max:50',
                'latitud' => 'required|numeric',
                'logitud' => 'required|numeric',
            ]
        );
        try{
        $detalleNegocioActualizado->update($validated);
        View::share('detallenegocioProviders', $detalleNegocioActualizado);
        Toaster::success('Se actualizo correctamente la ubicacion del negocio!');
        return redirect()->route('administrador.detallenegocio', [$detalleNegocioActualizado->id]);
        }
        catch (Exception $e){
            Toaster::error('Error al actualizar la ubicacion del negocio' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy(DetalleNegocio $detalleNegocio)
    {
        //
    }
}
