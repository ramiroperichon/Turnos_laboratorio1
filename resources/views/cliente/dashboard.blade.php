<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard cliente') }}
        </h2>
    </x-slot>

    <div class="page-header">
        <h3 class="page-title"> Bienvenido {{ Auth::user()->name }} </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Inicio</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Primera columna -->
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title mb-1">Servicios</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="preview-list">
                                @foreach ($servicios->take(5) as $servicio)
                                    <div class="preview-item border-bottom">
                                        <div class="preview-item-content d-flex flex-grow justify-content-between">
                                            <!-- Icono y título -->
                                            <div class="display-flex">
                                                <h6 class="preview-subject">{{ $servicio->nombre }}</h6>
                                                <p class="text-muted mb-0">{{ $servicio->descripcion }}</p>
                                            </div>
                                            <!-- Detalles de duración y precio -->
                                            <div>
                                                <p class="text-muted">Duración: {{ $servicio->duracion }}</p>
                                                <p class="text-muted mb-0">Precio: {{ $servicio->precio }}</p>
                                            </div>
                                            <!-- Botón de acción -->
                                            <div class="d-flex justify-content-end">
                                                <form action="{{ route('horario.selected', $servicio->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-info py-3" type="submit">Pedir turno</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <a href="{{ route('servicio.index') }}" class="btn btn-info my-4">Ver todos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda columna -->
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title mb-1">Mis turnos</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-primary-emphasis">Servicio</th>
                                    <th class="text-primary-emphasis">Fecha</th>
                                    <th class="text-primary-emphasis">Horario</th>
                                    <th class="text-primary-emphasis">Precio</th>
                                    <th class="text-primary-emphasis">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($reservas->count() == 0)
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <h4 class="text-muted">No hay servicios</h4>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($reservas as $reserva)
                                        <tr>
                                            <td class="text-secondary">{{ $reserva->servicio->nombre }}</td>
                                            <td>{{ $reserva->fecha_reserva }}</td>
                                            <td class="text-secondary">
                                                {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} a
                                                {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}
                                            </td>
                                            <td class="text-success-emphasis fw-bold">${{ $reserva->servicio->precio }}</td>
                                            <td>
                                                @if ($reserva->estado == 'Pendiente')
                                                    <span class="badge badge-outline-info">{{ $reserva->estado }}</span>
                                                @elseif($reserva->estado == 'Cancelado')
                                                    <span class="badge badge-outline-danger">{{ $reserva->estado }}</span>
                                                @elseif ($reserva->estado == 'Confirmado')
                                                    <span class="badge badge-outline-success">{{ $reserva->estado }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ $reserva->estado }}</span>
                                                @endif
                                            </td>
                                        </tr>
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
        document.querySelector(".number-input").addEventListener("keypress", function(evt) {
            if (evt.which != 8 && evt.which != 0 && (evt.which < 48 || evt.which > 57)) {
                evt.preventDefault();
            }
        });

        document.querySelector(".number-input1").addEventListener("keypress", function(evt) {
            if (evt.which != 8 && evt.which != 0 && (evt.which < 48 || evt.which > 57)) {
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
