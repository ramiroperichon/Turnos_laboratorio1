<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>

    </x-slot>
    <h1>Panel de Administrador</h1>

    <livewire:reservas-page :id-servicio="$idServicio ?? null" />
    <div class = "container mt-5">
        <livewire:servicios />
    </div>

    <ul>
        <li><a href="{{ route('administrador.reservas') }}">Ver Reservas</a></li>
        <li><a href="{{ route('administrador.servicios') }}">Ver Servicios</a></li>
        <li><a href="{{ route('administrador.detallenegocio') }}">Editar Detalles de Negocio</a></li>
        <li><a href="{{ route('administrador.proveedores') }}">Administrar Usuarios (Proveedores)</a></li>
    </ul>

</x-app-layout>
