<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use App\Services\ServicioService;
use App\Services\Validators\CheckAvailableDays;
use App\Services\Validators\IsInRange;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toaster;

class ServicioController extends Controller
{
    protected $servicioService;

    public function __construct(ServicioService $servicioService)
    {
        $this->servicioService = $servicioService;
    }

    public function index() //devuelve la vista de los servicios
    {
        $servicios = Servicio::get();
        return view('cliente.servicios', [
            'servicios' => $servicios
        ]);
    }

    public function userServices() //implementar seleccion por usuario
    {
        $servicios = Servicio::get();
        return view('proveedor.servicios', [
            'servicios' => $servicios
        ]);
    }

    public function create($id = null)
    {
        if (isset($id)) {
            return view('servicio.create', ['id' => $id]);
        }

        return view('servicio.create');
    }

    public function store(Request $request)
    {

        $inicio = \Carbon\Carbon::createFromFormat('H:i', $request->incio_turno);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $request->fin_turno);
        $differenceInMinutes = $inicio->diffInMinutes($fin);

        $user = new User();
        if ($request->id == null) {
            $user = auth()->user();
        } else {
            try {
                $user = User::findOrFail($request->id);
            } catch (ModelNotFoundException $e) {
                Toaster::error('Usuario no encontrado');
                return redirect()->back()->withInput();
            }
        }

        $validated = $request->validate(
            [
                'nombre' => 'required|min:3|max:30|unique:servicios,nombre',
                'descripcion' => 'required|min:5|max:255',
                'precio' => 'required|numeric|min:1',
                'incio_turno' => ['required', 'date_format:H:i', new IsInRange($user->proveedor->horario_inicio, $user->proveedor->horario_fin)],
                'fin_turno' => 'required|date_format:H:i|after:incio_turno',
                'duracion' => 'required|integer|min:5|max:' . $differenceInMinutes,
                'dias_disponible' => ['required', 'array', 'min:1', new CheckAvailableDays($user->id)],
                'dias_disponible.*' => 'in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            ],
            [
                'fin_turno.after' => 'La hora de fin no puede ser menor a la de inicio!',
                'duracion.max' => 'La duracion no debe ser mayor al tiempo del servicio'
            ]
        );

        $validated['dias_disponible'] = implode(',', $validated['dias_disponible']);

        $this->servicioService->storeServicioWithHorarios($validated, $user->id);

        Toaster::success('Servicio se creo correctamente!');
        if (auth()->user()->hasrole('administrador')) {
            return redirect('/administrador/servicios');
        }
        return redirect('/servicio/user');
    }

    public function update(Request $request, Servicio $servicio)
    {
        $differenceInMinutes = 0;
        if ($request->has('incio_turno') && $request->has('fin_turno')) {
            $inicio = \Carbon\Carbon::createFromFormat('H:i', $request->incio_turno);
            $fin = \Carbon\Carbon::createFromFormat('H:i', $request->fin_turno);
            $differenceInMinutes = $inicio->diffInMinutes($fin);
        }

        $validated = $request->validate(
            [
                'nombre' => 'required|min:3|max:30',
                'descripcion' => 'required|min:5|max:255',
                'precio' => 'required|numeric|min:1',
                'incio_turno' => 'nullable|date_format:H:i',
                'fin_turno' => 'nullable|date_format:H:i|after:incio_turno',
                'duracion' => 'nullable|integer|min:10|max:' . $differenceInMinutes,
                'dias_disponible' => 'nullable|array|min:1',
                'dias_disponible.*' => 'in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            ],
            [
                'fin_turno.after' => 'La hora de fin no puede ser menor a la de inicio!'
            ]
        );

        $user = new User();
        if ($request->userId == null) {
            $user = auth()->user();
        } else {
            try {
                $user = User::findOrFail($request->userId);
            } catch (ModelNotFoundException $e) {
                Toaster::error('Usuario no encontrado');
                return redirect('/');
            }
        }

        if ($request->has('incio_turno') && $request->has('fin_turno')) {
            if ($this->servicioService->IsInRange($user->proveedor->horario_inicio, $user->proveedor->horario_fin, $validated['incio_turno'], $validated['fin_turno']) == false) {
                Toaster::error('Horario invalido, esta fuera de los horarios de trabajo');
                return redirect('/servicio/user');
            }
            if ($request->has('dias_disponible')) {
                if ($this->servicioService->getAvialableHours($user->id, $validated['dias_disponible'], $validated['incio_turno'], $validated['fin_turno'], $servicio->id) == false) {
                    Toaster::error('Horario utilizado por otro servicio');
                    return redirect('/servicio/user');
                }
                $validated['dias_disponible'] = implode(',', $validated['dias_disponible']);
            }
        }

        $servicio = $this->servicioService->removeOldServicioHorariosAndUpdate($validated, $servicio);
        Toaster::success('Servicio actualizado correctamente!');
        return redirect('/servicio/user');
    }

    public function destroy($servicio)
    {
        $this->servicioService->DestroyServicio($servicio);
        return redirect('/servicio/user');
    }
}
