<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Horario') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title"> Crear reserva </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('servicio.all') }}">Servicios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reservar</li>
            </ol>
        </nav>
    </div>

    <div class="container d-flex justify-content-center">
        <form action="{{ route('reserva.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card items-center">
                        <div class="card-body">
                            <div class="d-flex flex-row justify-content-between border-bottom px-2 pt-2 pb-1 mb-3">
                                <div class="d-flex flex-row items-center space-x-2">
                                    <i class="icon-sm mdi mdi-wallet-travel text-primary"></i>
                                    <h4 class="card-title display-5 mb-1">S{{ $servicio->nombre }}</h4>
                                </div>
                                <p class="text-muted mb-1">Servicio N°{{ $servicio->id }}</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 flex justify-center">
                                    <div class="p-1" id="inline_cal"></div>
                                    <label hidden id="inl" required>{{ $servicio->dias_disponible }}</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-row items-center space-x-2 border-bottom py-1">
                                        <i class="icon-sm mdi mdi-clock text-muted"></i>
                                        <h6 class="text-muted fw-bold font-weight-normal p-0 m-0">Horarios
                                            disponibles</h6>
                                    </div>
                                    <livewire:dynamicselectinput :servicio="$servicio" :reservas="$reservas" />
                                    <x-input-error :messages="$errors->get('horario_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer container-fluid my-4">
                            <input type="number" hidden id="servicio_id" name="servicio_id" value="{{ $servicio->id }}"
                                required class="form-control" />
                            <x-input-error :messages="$errors->get('servicio_id')" class="mt-2" />
                            <x-input-error :messages="$errors->get('fecha_reserva')" class="mt-2" />
                            <input type="number" id="cliente_id" hidden name="cliente_id"
                                value="{{ Auth::user()->id }}" required class="form-control" />
                            <x-input-error :messages="$errors->get('fecha_reserva')" class="mt-2" />
                            <div class="text-center ">
                                <button class="btn btn-info me-2" id="submit-button" type="submit">Crear reserva</button>
                                <a class="btn btn-dark" href="{{ url()->previous() }}">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script></script>
</x-app-layout>
