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
                @hasrole('proveedor')
                <li class="breadcrumb-item"><a href="{{ route('servicio.userServices') }}">Mis servicios</a></li>
                @elsehasrole('administrador')
                <li class="breadcrumb-item"><a href="{{ route('administrador.servicios') }}">Servicios</a></li>
                @endhasrole
                <li class="breadcrumb-item active" aria-current="page">Reservas</li>
            </ol>
        </nav>
    </div>
    <div class="content-wrapper py-2 px-3 text-center">
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
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-row items-center pb-3">
                        <div class="flex-col item-center">
                        <div class="icon icon-box-info size-11 me-2">
                            <span class="mdi mdi-calendar-multiple icon-item"></span>
                        </div>
                    </div>
                        <div class="flex-col">
                            @hasrole('proveedor')
                                <h4 class="card-title text-start m-0"> Reservas
                                    <span class="font-weight-light">de {{ auth()->user()->name }}</span>
                                </h4>
                            @else
                                <h4 class="card-title text-start m-0"> Todas las reservas</h4>
                            @endhasrole
                            @if (isset($idServicio) && $idServicio)
                                <p class="text-muted text-start m-0">del servicio N° {{ $idServicio }}</p>
                            @endif
                        </div>
                    </div>
                    <livewire:reservas-page :id-servicio="$idServicio ?? null" />
                </div>
            </div>
        </div>
</x-app-layout>
