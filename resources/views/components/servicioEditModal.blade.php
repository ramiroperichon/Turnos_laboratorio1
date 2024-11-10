<div class="flex flex-row gap-2 justify-center grow items-center">
    <div class="flex-row group/algo">
        <button class="btn btn-info py-1 w-100" id="myInput{{ $getRecord()->id }}" data-bs-toggle="modal"
            data-bs-target="#myModal{{ $getRecord()->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg></button>
        <div
            class="absolute scale-0 invisible z-50 whitespace-normal break-words rounded-lg bg-gray-700 py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none transition-all duration-200 group-hover/algo:visible group-hover/algo:opacity-100 group-hover/algo:scale-100">
            Editar
        </div>
    </div>
    <div class="flex-row group/algo">
        <div class="relative">
            <button type="button" @if(!$getRecord()->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count() == 0) disabled @endif class="btn btn-danger w-100 py-1" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop{{ $getRecord()->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </button>
        </div>
        <div
            class="absolute scale-0 invisible z-50 whitespace-normal break-words rounded-lg bg-gray-700 py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none transition-all duration-200 group-hover/algo:visible group-hover/algo:opacity-100 group-hover/algo:scale-100">
            Borrar
        </div>
        <!-- Modal Borrar -->
        <div class="modal fade" id="staticBackdrop{{ $getRecord()->id }}"
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
                            action="{{ route('servicio.destroy', $getRecord()->id) }}"
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
    </div>
    <div class="flex-row group/algo">
        <a href="{{ route('reserva.selected', $getRecord()->id) }}">
            <div class="relative">
                <button class="btn btn-success w-100 py-1" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                    </svg>
                    <div
                        class="absolute flex h-4 w-4 m-0 p-0 items-center justify-center bg-purple-600 rounded-lg -left-1 -top-1">
                        <p class="p-0 m-0 text-purple-300 font-light">
                            {{ $getRecord()->reservas->whereIn('estado', ['Pendiente', 'Confirmado'])->count() }}</p>
                    </div>
                </button>
            </div>
            <div
                class="absolute scale-0 invisible z-50 whitespace-normal break-words rounded-lg bg-gray-700 py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none transition-all duration-200 group-hover/algo:visible group-hover/algo:opacity-100 group-hover/algo:scale-100">
                Ver reservas
            </div>
        </a>
    </div>
    <!-- Modal Editar -->
    <form action="{{ route('servicio.update', $getRecord()->id) }}" method="POST" class="flex-nowrap">
        @csrf
        @method('patch')
        <div class="modal fade" id="myModal{{ $getRecord()->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            Editar servicio
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row gx-5">
                            <div class="col mt-3">
                                <label>Nombre del servicio</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Ingrese un nombre"
                                    value="{{ $getRecord()->nombre }}" required class="form-control" />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>
                            <div class="col mt-3">
                                <label>Precio</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white">$</span>
                                    </div>
                                    <input type="number" id="precio" name="precio" placeholder="Ej: 20000" required
                                        value="{{ $getRecord()->precio }}" class="form-control number-input col-sm-5" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                    <x-input-error :messages="$errors->get('precio')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                            <textarea class="form-control" id="descripcion" placeholder="Ingrese la descripcion del servicio..." required
                                name="descripcion" rows="2">{{ $getRecord()->descripcion }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>
                        @if ($getRecord()->reservas->count() > 0)
                            <div class="mb-3 group">
                                <label>Inicio</label>
                                <input type="time"
                                    value="{{ \Carbon\Carbon::parse($getRecord()->incio_turno)->format('H:i') }}"
                                    max="00:00" disabled class="form-control" />
                                <x-tooltip :tip="$getRecord()->reservas->count()
                                    ? 'No puedes modificar los horarios si hay reservas pendientes o por completar'
                                    : ''" />
                                <x-input-error :messages="$errors->get('incio_turno')" class="mt-2" />
                            </div>
                            <div class="mb-3 group">
                                <label>Fin</label>
                                <input type="time" min="05:00"
                                    value="{{ \Carbon\Carbon::parse($getRecord()->fin_turno)->format('H:i') }}"
                                    max="00:00" disabled class="form-control" />
                                <x-tooltip :tip="$getRecord()->reservas->count()
                                    ? 'No puedes modificar los horarios si hay reservas pendientes o por completar'
                                    : ''" />
                                <x-input-error :messages="$errors->get('fin_turno')" class="mt-2" />
                            </div>
                            <div class="mb-3 group">
                                <label>Duracion minutos</label>
                                <div class="input-group">
                                    <input type="number" min="0" value="{{ $getRecord()->duracion }}"
                                        max="240" maxlength="2" disabled placeholder="Ej: 30"
                                        class="form-control number-input1 col-sm-5" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">Minutos</span>
                                    </div>
                                </div>
                                <x-tooltip :tip="$getRecord()->reservas->count()
                                    ? 'No puedes modificar los horarios si hay reservas pendientes o por completar'
                                    : ''" />
                                <x-input-error :messages="$errors->get('duracion')" />
                            </div>
                            <div class="mb-3">
                                <div>
                                    <label>Dias</label>
                                </div>
                                <div class="btn-group btn-group-sm flex-wrap mt-2 group" role="group"
                                    aria-label="Basic checkbox toggle button group">
                                    @foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'] as $day)
                                        <input type="checkbox" disabled class="btn-check" value="{{ $day }}"
                                            name="dias_disponible[]"
                                            id="btncheck{{ $getRecord()->id }}{{ $day }}"
                                            @if (in_array($day, explode(',', $getRecord()->dias_disponible))) checked @else @endif>
                                        <label class="btn btn-outline-info"
                                            for="btncheck{{ $getRecord()->id }}{{ $day }}">{{ $day }}</label>
                                    @endforeach
                                    <x-tooltip :tip="$getRecord()->reservas->count()
                                        ? 'No puedes modificar los horarios si hay reservas pendientes o por completar'
                                        : ''" />
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label>Inicio</label>
                                <input type="time" id="shift_start_modal" name="incio_turno" min="05:00"
                                    value="{{ \Carbon\Carbon::parse($getRecord()->incio_turno)->format('H:i') }}"
                                    max="00:00" required class="form-control" />
                                <x-input-error :messages="$errors->get('incio_turno')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label>Fin</label>
                                <input type="time" id="shift_end_modal" name="fin_turno" min="05:00"
                                    value="{{ \Carbon\Carbon::parse($getRecord()->fin_turno)->format('H:i') }}"
                                    max="00:00" required class="form-control" />
                                <x-input-error :messages="$errors->get('fin_turno')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label>Duracion minutos</label>
                                <div class="input-group">
                                    <input type="number" min="0" value="{{ $getRecord()->duracion }}"
                                        max="240" maxlength="2" placeholder="Ej: 30" id="duracion"
                                        name="duracion" required class="form-control number-input1 col-sm-5" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">Minutos</span>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('duracion')" />
                            </div>
                            <div class="mb-3">
                                <div>
                                    <label>Dias</label>
                                </div>
                                <div class="btn-group btn-group-sm flex-wrap mt-2" role="group"
                                    aria-label="Basic checkbox toggle button group">
                                    @foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'] as $day)
                                        <input type="checkbox" class="btn-check" value="{{ $day }}"
                                            name="dias_disponible[]"
                                            id="btncheck{{ $getRecord()->id }}{{ $day }}"
                                            @if (in_array($day, explode(',', $getRecord()->dias_disponible))) checked @else @endif>
                                        <label class="btn btn-outline-info"
                                            for="btncheck{{ $getRecord()->id }}{{ $day }}">{{ $day }}</label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cerrar</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalconfirmar{{ $getRecord()->id }}">Guardar
                                    cambios</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal de confirmar edicion-->
        <div class="modal fade" id="modalconfirmar{{ $getRecord()->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 text-danger" id="exampleModalToggleLabel2">Alerta!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-2 text-wrap text-center">
                        <div class="text-warning" role="alert">
                            <p>Seguro que quiere continuar?</p>
                            <p>Esta accion no se puede deshacer</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-outline-success" type="submit"
                            data-bs-target="#modalconfirmar{{ $getRecord()->id }}"
                            data-bs-toggle="modal">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Fin Modal Editar -->
</div>
