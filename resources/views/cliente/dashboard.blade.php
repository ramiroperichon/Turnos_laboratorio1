<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard cliente') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title"> Bienvenido {{Auth::user()->name}} </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Inicio</li>
            </ol>
        </nav>
    </div>
    @session('status')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="alert alert-danger alert-dismissible fade show">
            <ul class="p-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card rounded-3">
                <div class="card-body">
                    <h4 class="card-title">Servicios disponibles</h4>
                    @if ($servicios->count() == 0)
                        <div
                            class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                            <div class="text-md-center text-xl-left">
                                <h6 class="mb-1">No hay servicios disponibles</h6>
                                <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                            </div>
                            <div
                                class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                <h6 class="font-weight-bold mb-0">$236</h6>
                            </div>
                        </div>
                    @else
                        @foreach ($servicios as $servicio)
                            <div
                                class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                <div class="text-md-center text-xl-left">
                                    <h6 class="mb-1">{{ $servicio->nombre }}</h6>
                                    <p class="text-muted mb-1">{{ $servicio->descripcion }}</p>
                                    <p class="font-weight-bold mb-1 text-success">${{ $servicio->precio }}</p>
                                    <p class="font-weight-bold mb-0 text-muted">{{ $servicio->duracion }} Min.</p>
                                </div>
                                <div
                                    class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                    <form action="{{ route('horario.selected', $servicio->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-info py-3" type="submit">Pedir turno</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title mb-1">Mis turnos</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-primary-emphasis">Nombre del servicio</th>
                                    <th class="text-primary-emphasis">Fecha</th>
                                    <th class="text-primary-emphasis">Horario</th>
                                    <th class="text-primary-emphasis">Precio</th>
                                    <th class="text-primary-emphasis">Estado de reserva</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($reservas->count() == 0)
                                <td colspan="8" class="text-center">
                                    <h4 class="text-muted">No hay servicios</h4>
                                </td>
                                @else
                                    @foreach ($reservas as $reserva)
                                    <td class="text-secondary">{{$reserva->servicio->nombre}}</td>
                                    <td>{{$reserva->fecha_reserva}}</td>
                                    <td class="text-secondary">{{\Carbon\Carbon::parse($reserva->franjaHoraria->hora_inicio)->format('H:i')}} a {{\Carbon\Carbon::parse($reserva->franjaHoraria->hora_fin)->format('H:i')}}</td>
                                    <td class="text-success-emphasis fw-bold">${{$reserva->servicio->precio}}</td>
                                    <td> @if($reserva->estado == "Pendiente")
                                        <span class="badge badge-outline-info">{{$reserva->estado}}</span>
                                        @elseif($reserva->estado == "Cancelado")
                                        <span class="badge badge-outline-danger">{{$reserva->estado}}</span>
                                        @elseif ($reserva->estado == "Confirmado")
                                        <span class="badge badge-outline-success">{{$reserva->estado}}</span>
                                        @else
                                        <span class="badge badge-success">{{$reserva->estado}}</span>
                                        @endif</td>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector(".number-input").addEventListener("keypress", function(
            evt) { //evita poder colocar la e y los signos en los input de numeros
            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });

        document.querySelector(".number-input1").addEventListener("keypress", function(evt) {
            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });

        const shiftStart = document.getElementById('incio_turno');
        const shiftEnd = document.getElementById('fin_turno');

        shiftStart.addEventListener('change', function() {
            shiftEnd.min = shiftStart.value;
        });
    </script>

</x-app-layout>
