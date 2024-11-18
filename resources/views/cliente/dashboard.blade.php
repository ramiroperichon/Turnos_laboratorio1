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
                <div class="card-body py-3">
                    <div class="d-flex flex-row items-center justify-content-start ml-auto space-x-2">
                        <i class="icon-md mdi mdi-wallet-travel text-primary"></i>
                        <h4 class="card-title display-4 mb-1">Servicios</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="preview-list">
                                @foreach ($servicios as $servicio)
                                    <div class="preview-item border-bottom">
                                        <div class="preview-item-content d-flex flex-grow justify-content-between">
                                            <!-- Icono y título -->
                                            <div class="display-flex">
                                                <h6 class="preview-subject">{{ $servicio->nombre }}</h6>
                                                @if (strlen($servicio->descripcion) > 11)
                                                    <x-tooltip-arrow text="{{ $servicio->descripcion }}"
                                                        position="bottom">
                                                        <p class="text-muted mb-0 truncate w-20">
                                                            {{ $servicio->descripcion }}</p>
                                                    </x-tooltip-arrow>
                                                @else
                                                    <p class="text-muted mb-0 w-20 flex">{{ $servicio->descripcion }}</p>
                                                @endif
                                            </div>
                                            <!-- Detalles de duración y precio -->
                                            <div>
                                                <p class="text-muted flex m-0 mb-2 p-0"><i class="mdi mdi-clock me-1"></i> {{ $servicio->duracion }} Min.</p>
                                                <p class="flex text-success m-0 p-0"><i class="mdi mdi-currency-usd"></i> {{ $servicio->precio }}</p>
                                            </div>
                                            <!-- Botón de acción -->
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('reserva.create', $servicio->id) }}"
                                                    class="btn btn-primary py-3 flex"><i class="mdi mdi-calendar-star"></i>Reservar</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex flex-row justify-content-between items-center my-2">
                                    <a class="btn btn-primary" href="{{ route('servicio.all') }}">Ver todos</a>
                                    <p class="text-muted mb-1">Servicios disponibles: <span
                                            class="font-weight-bold text-primary">{{ $servicioscount }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda columna -->
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body py-3">
                    <div class="d-flex flex-row items-center justify-content-start ml-auto space-x-2">
                        <i class="icon-md mdi mdi-calendar-multiple text-info"></i>
                        <h4 class="card-title display-4 mb-1">Mis turnos</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped border-b border-gray-700/50">
                            <thead>
                                <tr>
                                    <th class="text-primary-emphasis fs-6">Servicio</th>
                                    <th class="text-primary-emphasis fs-6">Fecha</th>
                                    <th class="text-primary-emphasis fs-6">Horario</th>
                                    <th class="text-primary-emphasis fs-6">Precio</th>
                                    <th class="text-primary-emphasis fs-6">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($reservas->count() == 0)
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <h4 class="text-muted">No tienes ningun turno</h4>
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
                                            <td class="text-success-emphasis fw-bold">${{ $reserva->servicio->precio }}
                                            </td>
                                            <td>
                                                @if ($reserva->estado == 'Pendiente')
                                                    <span
                                                        class="badge bg-info-500/15 badge-outline-info">{{ $reserva->estado }}</span>
                                                @elseif($reserva->estado == 'Cancelado')
                                                    <span
                                                        class="badge bg-danger-500/15 badge-outline-danger">{{ $reserva->estado }}</span>
                                                @elseif ($reserva->estado == 'Confirmado')
                                                    <span
                                                        class="badge bg-success-500/15 badge-outline-success">{{ $reserva->estado }}</span>
                                                @else
                                                    <span class="badge bg-success-500/15 badge-success">{{ $reserva->estado }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex flex-row justify-content-between items-center my-2">
                            <p class="text-muted mb-1">Reservas: <span
                                    class="font-weight-bold text-info">{{ $reservascount }}</span>
                            </p>
                            <a class="btn btn-info" href="{{ route('servicio.all') }}">Ver todas</a>
                        </div>
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
