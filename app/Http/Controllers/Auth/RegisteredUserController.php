<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Proveedor;
use App\Models\Cliente;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.registernew');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role == "proveedor") {

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
        }

        if ($request->role == "cliente") {

            $request->validate([
                'documento' => ['required', 'string', 'max:8'],
            ]);

            Cliente::create([
            'usuario_id' => $user->id,
            'documento' => $request->documento,
            ]);

        }


        $user->assignRole($request->role);

        event(new Registered($user));

        Auth::login($user);

        if ($user->hasRole('proveedor')) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
