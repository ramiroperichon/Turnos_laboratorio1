<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear servicio') }}
        </h2>
    </x-slot>
    <div class="row">
        <div class="col grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agregar servicio
                    </h4>
                    <form action="{{ route('servicio.store') }}" method="POST">
                        @csrf
                        <div class="form-group row gx-5">
                            <div class="col mt-3">
                                <label>Nombre del servicio</label>
                                <input class="form-control @error('nombre') is-invalid @enderror" type="text"
                                    value="{{ old('nombre') }}" id="nombre" name="nombre"
                                    placeholder="Ingrese un nombre" required />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>
                            <div class="col mt-3">
                                <label>Precio</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white">$</span>
                                    </div>
                                    <input type="number" id="precio" name="precio" placeholder="Ej: 20000" required
                                        class="form-control number-input col-sm-3 @error('precio') is-invalid @enderror"
                                        value="{{ old('precio') }}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('precio')" class="mt-2" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror col-lg-6" id="descripcion" placeholder="Ingrese la descripcion del servicio..." required
                                name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>
                        <div class="form-group row gx-5">
                            <div class="col mt-3">
                                <label>Inicio</label>
                                <input type="time" id="incio_turno" name="incio_turno" min="05:00" value="08:00"
                                    max="00:00" required class="form-control @error('incio_turno') is-invalid @enderror"
                                    value="{{ old('incio_turno') }}" />
                                <x-input-error :messages="$errors->get('incio_turno')" class="mt-2" />
                            </div>
                            <div class="col mt-3">
                                <label>Fin</label>
                                <input type="time" id="fin_turno" name="fin_turno" min="07:00" value="19:00"
                                    max="20:00" required class="form-control @error('fin_turno') is-invalid @enderror"
                                    value="{{ old('fin_turno') }}" />
                                <x-input-error :messages="$errors->get('fin_turno')" class="mt-2" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label>Duracion minutos</label>
                                <div class="input-group">
                                    <input type="number" min="0" max="240" maxlength="2"
                                        placeholder="Ej: 30" id="duracion" name="duracion" required
                                        class="form-control number-input col-sm-5 @error('duracion') is-invalid @enderror"
                                        value="{{ old('duracion') }}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">Minutos</span>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('duracion')" />
                            </div>
                            <div class="col">
                                <label class="mb-2 px-2 row">Dias dispobible</label>
                                <div class="btn-group flex-wrap mt-2" role="group"
                                    aria-label="Basic checkbox toggle button group">
                                    @foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'] as $day)
                                        <input type="checkbox" class="btn-check" value="{{ $day }}"
                                            name="dias_disponible[]" id="btncheck{{ $day }}"
                                            autocomplete="off">
                                        <label class="btn btn-outline-info"
                                            for="btncheck{{ $day }}">{{ $day }}</label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('dias_disponible')" />
                            </div>
                        </div>
                        <div class="mt-5 text-center d-flex justify-content-center gap-3">
                            <button class="btn btn-primary btn-fw" type="submit">Crear servicio</button>
                            <a class="btn btn-dark btn-fw" href="/">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    </script>

</x-app-layout>
