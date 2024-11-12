<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Validators\CheckServicioFinSchedule;
use App\Services\Validators\CheckServicioInicioSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Masmerise\Toaster\Toaster;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->proveedor) {
            $request->validate(
                [
                    'profesion' => 'required|max:50',
                    'horario_inicio' => ['required', 'date_format:H:i', new CheckServicioInicioSchedule($user->id)],
                    'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
                    new CheckServicioFinSchedule($user->id),
                ],
                [
                    'horario_fin.after' => 'La hora de fin no puede ser menor a la de inicio!'
                ]
            );

            $user->proveedor->profesion = $request->input('profesion');
            $user->proveedor->horario_inicio = $request->input('horario_inicio');
            $user->proveedor->horario_fin = $request->input('horario_fin');
            $user->proveedor->save();
        }

        if ($user->cliente) {
            $request->validate(
                [
                    'documento' => 'required|max:50',
                ],
                [
                    'documento.required' => 'Debe ingresar un documento',
                ]
            );

            $user->cliente->documento = $request->input('documento');
            $user->cliente->save();
        }

        Toaster::success('Se actualizo correctamente el perfil');
        return Redirect::route('profile.edit');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
