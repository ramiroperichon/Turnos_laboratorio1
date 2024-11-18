<?php

namespace App\Http\Controllers;

use AnourValar\EloquentSerialize\Service;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DetalleNegocio;
use App\Models\Reserva;
use Illuminate\Support\Facades\Hash;
use App\Models\Proveedor;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Masmerise\Toaster\Toaster;

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
        foreach ($usuarios as $usuario) {
            $servicios = Servicio::where('proveedor_id', $usuario->id)->get();
            $usuario->servicios = $servicios;
        }

        return view('administrador.proveedores', data: [
            'usuarios' => $usuarios
        ]);
    }

/*     public function crearProveedor(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profesion' => ['required', 'string', 'max:20'],
            'horario_inicio' => ['required|date_format:H:i'],
            'horario_fin' => ['required|date_format:H:i'],
            'last_name' => 'required|min:2|max:50',
            'phone' => 'required|min:10|max:15',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_name' => $request->last_name,
            'phone' => $request->phone,
        ]);

        Proveedor::create([
            'usuario_id' => $user->id,
            'profesion' => $request->profesion,
            'horario_inicio' => $request->horario_inicio,
            'horario_fin' => $request->horario_fin
        ]);

        $user->assignRole('proveedor');

        return redirect()->route('administrador.proveedores')->with('success', 'Proveedor creado exitosamente');
    } */

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

    public function serviciosProveedor($idUsuario)
    {
        try {
            $usuario = User::find($idUsuario);
        } catch (\Exception $e) {
            Toaster::error('Error al buscar al usuario' . $e->getMessage());
            return redirect()->route('administrador.proveedores');
        }
        return view('proveedor.servicios', ['usuario' => $usuario]);
    }
}
