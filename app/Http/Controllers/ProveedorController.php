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
            'last_name' => 'required|min:2|max:50',
            'phone' => 'required|min:10|max:15',
        ]);

        try {

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
            Toaster::success('Proveedor ' . $user->name . ' creado exitosamente');
            return redirect()->route('administrador.proveedores');
        } catch (\Exception $e) {
            Toaster::error('Error al crear el proveedor ' . $e->getMessage());
        }
    }
}
