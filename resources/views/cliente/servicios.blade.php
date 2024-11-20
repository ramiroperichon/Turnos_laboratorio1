<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Servicios') }}
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
    <div class="row">
        <div class="col grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-row items-center pb-3">
                        <div class="flex-col item-center">
                            <div class="icon icon-box-warning size-11 me-2">
                                <span class="mdi mdi-bulletin-board icon-item"></span>
                            </div>
                        </div>
                        <div class="flex-col">
                                <h4 class="card-title text-start m-0"> Todos los servicios</h4>
                        </div>
                    </div>
                    <livewire:servicios />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
