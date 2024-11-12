<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Masmerise\Toaster\Toaster;

class ProveedorController extends Controller
{
    public function createProveedor()
    {

        return view('administrador.crear-proveedor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profesion' => ['required', 'string', 'max:20'],
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i',
        ]);

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            Proveedor::create([
                'usuario_id' => $user->id,
                'profesion' => $request->profesion,
                'horario_inicio' => $request->horario_inicio,
                'horario_fin' => $request->horario_fin
            ]);

            $user->assignRole('proveedor');
        } catch (\Exception $e) {
            Toaster::error('Error al crear el proveedor ' . $e->getMessage());
        }

        Toaster::success('Proveedor' . $user->name . ' creado exitosamente');
        return redirect()->route('administrador.proveedores');
    }
}
