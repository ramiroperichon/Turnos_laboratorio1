<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use App\Services\ServicioService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toast;
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
        return view('dashboard', [
            'servicios' => $servicios
        ]);
    }

    public function userServices()//implementar seleccion por usuario
    {
        $servicios = Servicio::get();
        return view('proveedor.servicios', [
            'servicios' => $servicios
        ]);
    }

    public function create()
    {

        return view('servicio.create');
    }

    public function store(Request $request)
    {

        $inicio = \Carbon\Carbon::createFromFormat('H:i', $request->incio_turno);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $request->fin_turno);
        $differenceInMinutes = $inicio->diffInMinutes($fin);

        $validated = $request->validate(
            [ //valida los datos ingresados
                'nombre' => 'required|min:3|max:30|unique:servicios,nombre',
                'descripcion' => 'required|min:5|max:255',
                'precio' => 'required|numeric',
                'incio_turno' => 'required|date_format:H:i',
                'fin_turno' => 'required|date_format:H:i|after:incio_turno',
                'duracion' => 'required|integer|min:10|max:' . $differenceInMinutes,
                'dias_disponible' => 'required|array|min:1',
                'dias_disponible.*' => 'in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            ],
            [
                'fin_turno.after' => 'La hora de fin no puede ser menor a la de inicio!',
            ]
        );

        $user = new User();
        if($request-> userId == null){
            $user = auth()->user();
        }
        else{
            try{
            $user = User::findOrFail($request->userId);
            }catch(ModelNotFoundException $e){
                return redirect('/')->with('status', 'Usuario no encontrado');
            }
        }

        if($this->servicioService->IsInRange($user->proveedor->horario_inicio, $user->proveedor->horario_fin, $validated['incio_turno'], $validated['fin_turno']) == false){
            return redirect('/servicio/create')->with('status', 'El turno no esta dentro de los horarios disponibles');//agregar status a formulario
        }

        if($this->servicioService->getAvialableHours($user->id, $validated['dias_disponible'], $validated['incio_turno'], $validated['fin_turno']) == false){
            return redirect('/servicio/create')->with('status', 'No hay horarios disponibles para este servicio');//agregar status a formulario
        }

        $validated['dias_disponible'] = implode(',', $validated['dias_disponible']);

        $servicio = $this->servicioService->storeServicioWithHorarios($validated, $user->id);

        return redirect('/')->with('status', 'Servicio se creo correctamente!');
    }

    public function show(string $id) //no implementado
    {
        return view('servicio.show');
    }

    public function edit(string $id) //no implementado
    {
        return view('servicio.edit');
    }

    public function update(Request $request, Servicio $servicio)
    {
        $inicio = \Carbon\Carbon::createFromFormat('H:i', $request->incio_turno);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $request->fin_turno);
        $differenceInMinutes = $inicio->diffInMinutes($fin);

        $validated = $request->validate(
            [ //valida los datos ingresados
                'nombre' => 'required|min:3|max:30',
                'descripcion' => 'required|min:5|max:255',
                'precio' => 'required|numeric',
                'incio_turno' => 'required|date_format:H:i',
                'fin_turno' => 'required|date_format:H:i|after:incio_turno',
                'duracion' => 'required|integer|min:10|max:' . $differenceInMinutes,
                'dias_disponible' => 'required|array|min:1', 
                'dias_disponible.*' => 'in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            ],
            [
                'fin_turno.after' => 'La hora de fin no puede ser menor a la de inicio!', //error si el usuario puso una horario de fin menor o igual al de inicio
            ]
        );

        $user = new User();
        if($request-> userId == null){
            $user = auth()->user();
        }
        else{
            try{
            $user = User::findOrFail($request->userId);
            }catch(ModelNotFoundException $e){
                return redirect('/')->with('error', 'Usuario no encontrado');
            }
        }

        if($this->servicioService->IsInRange($user->proveedor->horario_inicio, $user->proveedor->horario_fin, $validated['incio_turno'], $validated['fin_turno']) == false){
            Toaster::error('Error al eliminar reservas');
            return redirect('/servicio/user')->with('error', 'Horario invalido, esta fuera de los horarios de trabajo');

            //agregar status a formulario
        }

        if($this->servicioService->getAvialableHours($user->id, $validated['dias_disponible'], $validated['incio_turno'], $validated['fin_turno'], $servicio->id) == false){
            Toaster::error('Error al eliminar reservas');
            return redirect('/servicio/user')->with('error', 'Horario utilizado por otro servicio');//agregar status a formulario
        }


        $validated['dias_disponible'] = implode(',', $validated['dias_disponible']);

        $servicio = $this->servicioService->removeOldServicioHorariosAndUpdate($validated, $servicio);

        return redirect('/servicio/user')->with('status', 'Servicio actualizado correctamente!');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect('/servicio/user')->with('status', 'Servicio borrado correctamente!');
    }
}
