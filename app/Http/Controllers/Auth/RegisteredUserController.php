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
use Masmerise\Toaster\Toaster;

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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'last_name' => 'required|min:2|max:50',
            'phone' => 'required|min:10|max:15',
            'documento' => ['required', 'string', 'max:8, min:8'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_name' => $request->last_name,
                'phone' => $request->phone,
            ]);

            Cliente::create([
                'usuario_id' => $user->id,
                'documento' => $request->documento,
            ]);

            $user->assignRole('cliente');

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            Toaster::error('Error al crear el cliente ' . $e->getMessage());
        }
    }
}
