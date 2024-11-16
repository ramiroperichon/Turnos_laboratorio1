<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title">Servicios </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Servicios</li>
            </ol>
        </nav>
    </div>
    <div>
        <livewire:servicios />
    </div>
</x-app-layout>
