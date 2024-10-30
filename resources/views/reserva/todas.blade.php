<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reservas') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title"> Reservas </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reservas</li>
            </ol>
        </nav>
    </div>
    <div class="container text-center">
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
        <div class="row bg-body-tertiary p-6 p-lg-3 rounded-4 align-items-lg-center">
            <div class="col">
                        <h6 class="text-secondary fw-bold font-weight-normal">#</h6>
            </div>
            <div class="col">
                        <h6 class="text-secondary fw-bold font-weight-normal">Cliente</h6>
            </div>
            <div class="col">
                        <h6 class="text-secondary fw-bold font-weight-normal">Servicio</h6>
            </div>
            <div class="col">
                        <h6 class="text-secondary fw-bold font-weight-normal">Horario</h6>
            </div>
            <div class="col">
                        <h6 class="text-secondary fw-bold font-weight-normal">Fecha de reserva</h6>
            </div>
            <div class="col">
                <h6 class="text-secondary fw-bold font-weight-normal">Estado</h6>
            </div>
            <div class="col">
                <h6 class="text-secondary fw-bold font-weight-normal">Acciones</h6>
            </div>
        </div>
        @foreach ($reservas as $reserva )
        <div class="row p-6 my-3 rounded-4 align-items-lg-center" style="background-color: #191c24;">
            <div class="col">
                        <h6 class="text-muted font-weight-normal">{{$reserva->id}}</h6>
            </div>
            <div class="col">
                        <h6 class="text-muted font-weight-normal">{{$reserva->user->name}}</h6>
            </div>
            <div class="col">
                        <h6 class="text-muted font-weight-normal">{{$reserva->servicio->nombre}}</h6>
            </div>
            <div class="col">
                        <h6 class="text-muted font-weight-normal">{{\Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i')}} a</h6>
                        <h6 class="text-muted font-weight-normal">{{\Carbon\Carbon::parse($reserva->hora_fin)->format('H:i')}}</h6>
            </div>
            <div class="col">
                        <h6 class="text-muted font-weight-normal">{{$reserva->fecha_reserva}}</h6>
            </div>
            <div class="col">
                @if($reserva->estado == "Pendiente")
                <span class="badge badge-outline-info">{{$reserva->estado}}</span>
                @elseif($reserva->estado == "Cancelado")
                <span class="badge badge-outline-danger">{{$reserva->estado}}</span>
                @elseif ($reserva->estado == "Confirmado")
                <span class="badge badge-outline-success">{{$reserva->estado}}</span>
                @else
                <span class="badge badge-success">{{$reserva->estado}}</span>
                @endif
            </div>
            <div class="col">
                <button type="button" class="btn btn-danger mx-2 my-1 px-4"
                @if ($reserva->estado != 'Cancelado') disabled @endif
                 data-bs-toggle="modal"
                data-bs-target="#staticBackdrop{{ $reserva->id }}">
                Borrar
            </button>
            <form action="{{ route('reserva.update', $reserva->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" hidden name="estado" value="Confirmado" id="estado">
                <button type="submit" class="btn btn-success mx-2 my-1"
                    @if ($reserva->estado != 'Pendiente') disabled @endif>
                    Confirmar
                </button>
            </form>
            <form action="{{ route('reserva.update', $reserva->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" hidden name="estado" value="Cancelado" id="estado">
            <button type="submit" class="btn btn-warning mx-2 my-1"
                @if ($reserva->estado != 'Pendiente') disabled @endif>
                Rechazar
            </button>
        </form>
            <!-- Modal Borrar -->
            <div class="modal fade" id="staticBackdrop{{ $reserva->id }}"
                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal
                                title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Esta seguro que quiere borrar esta reserva?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                            <form action="{{ route('reserva.destroy', $reserva->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                    type="submit">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        @endforeach
        <livewire:reservas-table/>
    </div>
</x-app-layout>
