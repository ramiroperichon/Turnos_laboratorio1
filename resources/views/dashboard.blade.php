 <x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="row">
        <div class="col grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title display-1 font-semibold mb-1">Bienvenido al sistema de turnos de
                        {{ $detallenegocioProviders->nombre }}</h4>
                    <p class="text-muted fs-5 mt-1">Aquí podrás ver los servicios que ofrece el proveedor y pedir turnos
                    </p>
                    <p class="text-body-secondary fs-4 mt-1">Aquí encontrarás todos los servicios disponibles. Puedes ver
                        los detalles de cada servicio y reservar tu turno fácilmente.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-header">
        <h1 class="display-3"> Todos los servicios </h1>
    </div>
    <livewire:guest-servicios />

    </x-app-layout>
