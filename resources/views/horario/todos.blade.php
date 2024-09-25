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
                <li class="breadcrumb-item active" aria-current="page">Reservar</li>
            </ol>
        </nav>
    </div>

    <div class="container d-flex justify-content-center">
        <form action="{{ route('reserva.store') }}" method="POST">
            @csrf
            <div class="card items-center">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between border-bottom px-2 pt-2 pb-1 mb-3">
                        <h4 class="card-title mb-1">{{ $servicio->nombre }}</h4>
                        <p class="text-muted mb-1">Servicio N°{{ $servicio->id }}</p>
                    </div>
                    <div class="row gap-2">
                        <div class="col text-center">
                            <div class="p-1" id="inline_cal"></div>
                            <label hidden id="inl" required>{{ $servicio->dias_disponible }}</label>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold font-weight-normal border-bottom py-1">Horarios disponibles
                            </h6>
                            <div class="row g-2">
                                @foreach ($horarios as $horario)
                                    <div class="col-lg-2">
                                        <input type="radio" required id="option{{ $horario->id }}" name="horario_id"
                                            value="{{ $horario->id }}" class="btn-check"
                                            @if ($horario->disponibilidad == 'Reservado') disabled @endif>
                                        <label class="btn btn-primary btn-rounded w-100"
                                            for="option{{ $horario->id }}">
                                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <x-input-error :messages="$errors->get('horario_id')" class="mt-2" />
                        </div>
                    </div>
                </div>
                <div class="card-footer container-fluid my-4">
                    <input type="number" hidden id="servicio_id" name="servicio_id" value="{{ $servicio->id }}"
                        required class="form-control" />
                    <x-input-error :messages="$errors->get('servicio_id')" class="mt-2" />
                    <input type="date" id="result" hidden name="fecha_reserva" required class="form-control" />
                    <x-input-error :messages="$errors->get('fecha_reserva')" class="mt-2" />
                    <input type="number" id="cliente_id" hidden name="cliente_id" value="{{ Auth::user()->id }}"
                        required class="form-control" />
                    <x-input-error :messages="$errors->get('fecha_reserva')" class="mt-2" />
                    <div class="text-center ">
                        <button class="btn btn-info" id="submit-button" type="submit">Crear reserva</button>
                        <a class="btn btn-dark" type="button" href="/">Volver</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script></script>
</x-app-layout>
