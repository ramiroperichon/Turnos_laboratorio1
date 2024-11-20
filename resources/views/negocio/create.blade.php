<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title">Detalle del negocio </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalle del negocio</li>
            </ol>
        </nav>
    </div>
    <div class="pb-12">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Editar detalles del negocio</h4>
                        <p class="card-description">
                            Ingrese los datos del negocio, incluyendo información de contacto, redes sociales y numero
                            telefonico.
                        </p>
                        <form action="{{ route('administrador.detallenegocioupdate', $old->id) }}" class="mt-4"
                            method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="nombre">Nombre del negocio</label>
                                <input type="text" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    placeholder="Ingrese el nombre del negocio"
                                    value="{{ $old->nombre ?? old('nombre') }}" required>
                                <x-input-error :messages="$errors->get('nombre')" />
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Ingrese email del negocio" value="{{ $old->email ?? old('email') }}"
                                    required>
                                <x-input-error :messages="$errors->get('email')" />
                            </div>

                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    placeholder="Ingrese el numero de telefono"
                                    value="{{ $old->telefono ?? old('telefono') }}" required>
                                <x-input-error :messages="$errors->get('telefono')" />
                            </div>
                            <div class="form-group">
                                <label for="Iurl">Instagram</label>
                                <input type="url" name="Iurl"
                                    class="form-control @error('Iurl') is-invalid @enderror"
                                    placeholder="Ingrese la url del instagram del negocio"
                                    value="{{ $old->Iurl ?? old('Iurl') }}">
                                <x-input-error :messages="$errors->get('Iurl')" />
                            </div>

                            <div class="form-group">
                                <label for="Furl">Facebook</label>
                                <input type="url" name="Furl"
                                    class="form-control @error('Furl') is-invalid @enderror"
                                    placeholder="Ingrese la url del facebook del negocio"
                                    value="{{ $old->Furl ?? old('Furl') }}">
                                <x-input-error :messages="$errors->get('Furl')" />
                            </div>

                            <div class="form-group">
                                <label for="Turl">TikTok</label>
                                <input type="url" name="Turl"
                                    class="form-control @error('Turl') is-invalid @enderror"
                                    placeholder="Ingrese la url del tiktok del negocio"
                                    value="{{ $old->Turl ?? old('Turl') }}">
                                <x-input-error :messages="$errors->get('Turl')" />
                            </div>

                            <div class="form-group">
                                <label for="Xurl">Twitter(X)</label>
                                <input type="url" name="Xurl"
                                    class="form-control @error('Xurl') is-invalid @enderror"
                                    placeholder="Ingrese la url del twitter del negocio"
                                    value="{{ $old->Xurl ?? old('Xurl') }}">
                                <x-input-error :messages="$errors->get('Xurl')" />
                            </div>
                            <div class="flex items-center gap-4">
                                <button class="btn btn-primary fs-5 font-weight-bold"
                                    type="submit">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Editar ubicacion del negocio</h4>
                        <p class="card-description">
                            Asegurese que la ubicacion este correcta, puede arrastrar el mapa para ajustar la ubicacion.
                        </p>
                        <form action="{{ route('administrador.detallenegocioupdateUbicacion', $old->id) }}"
                            class="mt-4" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" name="direccion" class="form-control" id="autocomplete"
                                    value="{{ $old->direccion }}" />
                            </div>

                            <input type="text" id="latitud" name="latitud" hidden
                                class="form-control bg-body-secondary" value="{{ $old->latitud }}" required>
                            <x-input-error :messages="$errors->get('latitud')" />

                            <input type="text" id="logitud" name="logitud" hidden
                                class="form-control bg-body-secondary" value="{{ $old->logitud }}" required>
                            <x-input-error :messages="$errors->get('logitud')" />

                            <div class="form-group">
                                <label for="map">Ubicacion</label>
                                <div style="height: 435px; width: 100%;" id="mapedit"></div>
                            </div>
                            <div class="flex items-center gap-4">
                                <button class="btn btn-primary fs-5 font-weight-bold"
                                    type="submit">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/mapwithinput.js') }}"></script>
</x-app-layout>
