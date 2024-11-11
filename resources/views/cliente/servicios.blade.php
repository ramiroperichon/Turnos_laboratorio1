<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title"> Servicios </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Servicios</li>
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
        <div class="col grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Todos los servicios</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="fw-bold text-primary-emphasis">#</th>
                                    <th class="fw-bold text-primary-emphasis">Nombre</th>
                                    <th class="fw-bold text-primary-emphasis">Descripcion</th>
                                    <th class="fw-bold text-primary-emphasis">Precio</th>
                                    <th class="fw-bold text-primary-emphasis">Inicio de turno</th>
                                    <th class="fw-bold text-primary-emphasis">Fin de turno</th>
                                    <th class="fw-bold text-primary-emphasis">Duracion</th>
                                    <th class="fw-bold text-primary-emphasis">Dias disponible</th>
                                    <th class="fw-bold text-primary-emphasis">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if ($servicios->count() != 0)
                                    @foreach ($servicios as $servicio)
                                        <tr>
                                            <td>{{ $servicio->id }}</td>
                                            <td>{{ $servicio->nombre }}</td>
                                            <td class="text-truncate text-wrap" style="max-width: 30px;">
                                                {{ $servicio->descripcion }}</td>
                                            <td>${{ $servicio->precio }}</td>
                                            <td>{{ \Carbon\Carbon::parse($servicio->incio_turno)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($servicio->fin_turno)->format('H:i') }}</td>
                                            <td>{{ $servicio->duracion }} Min.</td>
                                            <td>{{ $servicio->dias_disponible }}</td>
                                            <!-- Borrar y editar -->
                                            <td>
                                                @hasrole('cliente')
                                                    <div class="col my-1">
                                                        <a href="{{ route('reserva.create', $servicio->id) }}"
                                                            class="btn btn-success">Pedir turno</a>
                                                    </div>
                                                @endhasrole
                                        </tr>
                                    @endforeach
                                @else
                                    <td colspan="8" class="text-center">
                                        <h4 class="text-muted">No hay servicios</h4>
                                    </td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
