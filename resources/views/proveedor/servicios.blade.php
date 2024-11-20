<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Servicios de proveedor') }}
        </h2>
    </x-slot>
    @hasrole('proveedor')
        <div class="page-header">
            <h3 class="page-title"> Servicios </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mis servicios</li>
                </ol>
            </nav>
        </div>
    @endhasrole
    @hasrole('administrador')
        <div class="page-header">
            <h3 class="page-title"> Servicios del proveedor NÂ° {{ $usuario->id }} </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administrador.proveedores') }}">Proveedores</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Servicios</li>
                </ol>
            </nav>
        </div>
    @endhasrole
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
                            @hasrole('proveedor')
                                <h4 class="card-title text-start m-0"> Servicios
                                    <span class="font-weight-light">de {{ auth()->user()->name }}</span>
                                </h4>
                            @endhasrole
                            @hasrole('administrador')
                                <h4 class="card-title text-start m-0"> Servicios
                                    <span class="font-weight-light">de {{ $usuario->name }}</span>
                                </h4>
                            @endhasrole
                        </div>
                    </div>
                    <livewire:servicios :id-proveedor="isset($usuario) ? $usuario->id : auth()->user()->id" />
                    <script>
                        document.querySelector(".number-input").addEventListener("keypress", function(
                            evt) { //evita poder colocar la e y los signos en los input de numeros
                            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                                evt.preventDefault();
                            }
                        });

                        document.querySelector(".number-input1").addEventListener("keypress", function(evt) {
                            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                                evt.preventDefault();
                            }
                        });

                        const shiftStart = document.getElementById('shift_start_modal');
                        const shiftEnd = document.getElementById('shitf_end_modal');

                        shiftStart.addEventListener('change', function() {
                            shiftEnd.min = shiftStart.value;
                        });
                    </script>

</x-app-layout>
