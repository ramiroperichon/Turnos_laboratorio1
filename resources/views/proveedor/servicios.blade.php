<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard proveedor') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title"> Servicios </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mis servicios</li>
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
    @session('error')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
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
                    <div class="flex flex-row items-center pb-3">
                        <div class="flex-col item-center">
                        <div class="icon icon-box-warning size-11 me-2">
                            <span class="mdi mdi-calendar-multiple icon-item"></span>
                        </div>
                    </div>
                        <div class="flex-col">
                            @hasrole('proveedor')
                                <h4 class="card-title text-start m-0"> Servicios
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
                    <livewire:servicios />
{{--                     <div class="table-responsive">
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
                                    <th class="fw-bold text-primary-emphasis text-center">Dias disponible</th>
                                    <th class="fw-bold text-primary-emphasis text-center">Acciones</th>
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
                                                <div class="row gap-2 flex-wrap">
                                                    <div class="col">
                                                        <button class="btn btn-info w-100"
                                                            id="myInput{{ $servicio->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#myModal{{ $servicio->id }}">Editar</button>
                                                    </div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-danger w-100"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop{{ $servicio->id }}">
                                                            Borrar
                                                        </button>
                                                    </div>
                                                    <div class="col">
                                                        <form action="{{ route('reserva.selected', $servicio->id) }}"
                                                            method="GET">
                                                            @csrf
                                                            <button class="btn btn-success w-100" type="submit">Ver
                                                                reservas</button>
                                                        </form>
                                                    </div>
                                                    <!-- Modal Editar -->
                                                    <form action="{{ route('servicio.update', $servicio->id) }}"
                                                        method="POST" class="flex-nowrap">
                                                        @csrf
                                                        @method('patch')
                                                        <div class="modal fade" id="myModal{{ $servicio->id }}">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="exampleModalLabel">
                                                                            Editar servicio
                                                                        </h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group row gx-5">
                                                                            <div class="col mt-3">
                                                                                <label>Nombre del servicio</label>
                                                                                <input type="text" id="nombre"
                                                                                    name="nombre"
                                                                                    placeholder="Ingrese un nombre"
                                                                                    value="{{ $servicio->nombre }}"
                                                                                    required class="form-control" />
                                                                                <x-input-error :messages="$errors->get('nombre')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                            <div class="col mt-3">
                                                                                <label>Precio</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span
                                                                                            class="input-group-text bg-success text-white">$</span>
                                                                                    </div>
                                                                                    <input type="number" id="precio"
                                                                                        name="precio"
                                                                                        placeholder="Ej: 20000" required
                                                                                        value="{{ $servicio->precio }}"
                                                                                        class="form-control number-input col-sm-5" />
                                                                                    <div class="input-group-append">
                                                                                        <span
                                                                                            class="input-group-text">.00</span>
                                                                                    </div>
                                                                                    <x-input-error :messages="$errors->get('precio')"
                                                                                        class="mt-2" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="descripcion">Descripcion</label>
                                                                            <textarea class="form-control" id="descripcion" placeholder="Ingrese la descripcion del servicio..." required
                                                                                name="descripcion" rows="2">{{ $servicio->descripcion }}</textarea>
                                                                            <x-input-error :messages="$errors->get('descripcion')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Inicio</label>
                                                                            <input type="time"
                                                                                id="shift_start_modal"
                                                                                name="incio_turno" min="05:00"
                                                                                value="{{ \Carbon\Carbon::parse($servicio->incio_turno)->format('H:i') }}"
                                                                                max="00:00" required
                                                                                class="form-control" />
                                                                            <x-input-error :messages="$errors->get('incio_turno')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Fin</label>
                                                                            <input type="time" id="shift_end_modal"
                                                                                name="fin_turno" min="05:00"
                                                                                value="{{ \Carbon\Carbon::parse($servicio->fin_turno)->format('H:i') }}"
                                                                                max="00:00" required
                                                                                class="form-control" />
                                                                            <x-input-error :messages="$errors->get('fin_turno')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Duracion minutos</label>
                                                                            <div class="input-group">
                                                                                <input type="number" min="0"
                                                                                    value="{{ $servicio->duracion }}"
                                                                                    max="240" maxlength="2"
                                                                                    placeholder="Ej: 30"
                                                                                    id="duracion" name="duracion"
                                                                                    required
                                                                                    class="form-control number-input1 col-sm-5" />
                                                                                <div class="input-group-append">
                                                                                    <span
                                                                                        class="input-group-text">Minutos</span>
                                                                                </div>
                                                                            </div>
                                                                            <x-input-error :messages="$errors->get('duracion')" />
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <div>
                                                                                <label>Dias</label>
                                                                            </div>
                                                                            <div class="btn-group btn-group-sm flex-wrap mt-2"
                                                                                role="group"
                                                                                aria-label="Basic checkbox toggle button group">
                                                                                @foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'] as $day)
                                                                                    <input type="checkbox"
                                                                                        class="btn-check"
                                                                                        value="{{ $day }}"
                                                                                        name="dias_disponible[]"
                                                                                        id="btncheck{{ $servicio->id }}{{ $day }}"
                                                                                        @if (in_array($day, explode(',', $servicio->dias_disponible))) checked @else @endif>
                                                                                    <label class="btn btn-outline-info"
                                                                                        for="btncheck{{ $servicio->id }}{{ $day }}">{{ $day }}</label>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-secondary"
                                                                                    data-bs-dismiss="modal">Cerrar</button>
                                                                            </div>
                                                                            <div class="col">
                                                                                <button class="btn btn-warning"
                                                                                    type="button"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalconfirmar{{ $servicio->id }}">Guardar
                                                                                    cambios</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--Modal de confirmar edicion-->
                                                        <div class="modal fade"
                                                            id="modalconfirmar{{ $servicio->id }}" tabindex="-1">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5 text-danger"
                                                                            id="exampleModalToggleLabel2">Alerta!</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body p-2 text-wrap text-center">
                                                                        <div class="text-warning d-flex"
                                                                            role="alert">
                                                                            Al realizar estos cambios se cancelaran
                                                                            todos
                                                                            las
                                                                            reservas de este servicio!
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <button class="btn btn-outline-success"
                                                                            type="submit"
                                                                            data-bs-target="#modalconfirmar{{ $servicio->id }}"
                                                                            data-bs-toggle="modal">Confirmar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- Fin Modal Editar -->
                                                </div>
                                                <!-- Modal Borrar -->
                                                <div class="modal fade" id="staticBackdrop{{ $servicio->id }}"
                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                    Borrar servicio</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Esta seguro que quiere borrar este servicio?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cerrar</button>
                                                                <form
                                                                    action="{{ route('servicio.destroy', $servicio->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button class="btn btn-danger"
                                                                        type="submit">Borrar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Fin Modal Borrar -->
                                            </td>
                                            <!-- Fin de borrar/editar-->
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
        </div> --}}

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
