<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DetalleNegocio;
use App\Models\Reserva;

class AdministradorController extends Controller
{
    public function dashboard()
    {
        return view('administrador.dashboard');
    }

    public function reservas()
    {

        $reservas = Reserva::all();

        return view('administrador.reservas', data: ['reservas' => $reservas]);

    }
    public function servicios()
    {

        $servicios = Servicio::all();

        return view('administrador.servicios', compact('servicios'));

    }
    public function usuariosall()
    {

        $usuarios = User::role('proveedor')->get();

        return view('administrador.proveedores', data: [
            'usuarios' => $usuarios
        ]);
    }

    public function crearProveedor(Request $request)
    {
        // drea un nuevo proveedor
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->assignRole('proveedor');

        return redirect()->route('administrador.proveedores')->with('success', 'Proveedor creado exitosamente');
    }

    public function modificarProveedor(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('administrador.proveedores')->with('success', 'Datos del proveedor actualizados');
    }

    public function usuarios()
    {
        $usuarios = User::role('proveedor')->get();
        
        return view('administrador.proveedores  ', data: ['usuarios' => $usuarios]);
    }

    public function detallesnegocio()
    {

        $detallenegocio = DetalleNegocio::first();

        return view('negocio.create', data: ['old' => $detallenegocio]);

    }

    public function editarServicios($idUsuario)
    {

        //$servicio = Servicio::where('proveedor_id', $idUsuario)->get();
        $servicio = Servicio::where('proveedor_id', $idUsuario)->get();

        return view('administrador.editarServicios', data: ['usuario' => $idUsuario, 'servicios' => $servicio]);
    }
}
