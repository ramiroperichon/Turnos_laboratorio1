<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;

class ProveedorController extends Controller
{
    public function createProveedor()
    {

        return view('administrador.crear-proveedor');

    }

    /*public function updateProveedor(ProfileUpdateRequest $request)
    {

        $user = $request->user();
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->proveedor) {
            $user->proveedor->profesion = $request->input('profesion');
            $user->proveedor->horario_inicio = $request->input('horario_inicio');
            $user->proveedor->horario_fin = $request->input('horario_fin');
            $user->proveedor->save();
        }

        return Redirect::route('administrador.proveedores');
    }*/

    public function store(Request $request)
    {


        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $request->validate([
            'profesion' => ['required', 'string', 'max:50'],
            'horario_inicio' => ['required'],
            'horario_fin' => ['required'],
        ]);


        Proveedor::create([
            'usuario_id' => $user->id,
            'profesion' => $request->profesion,
            'horario_inicio' => $request->horario_inicio,
            'horario_fin' => $request->horario_fin
        ]);

        return redirect()->route('administrador.proveedores');
    }
}
