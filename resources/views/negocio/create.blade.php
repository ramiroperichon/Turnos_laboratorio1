<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>
    </x-slot>
        <form action="{{ route('detalleNegocio.update', $old->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card ml-25 mr-25">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $old->nombre }}" required>
                        <x-input-error :messages="$errors->get('nombre')" />
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ $old->email }}" required>
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" class="form-control" value="{{ $old->telefono }}" required>
                        <x-input-error :messages="$errors->get('telefono')" />
                    </div>

                    <div class="form-group">
                        <label for="latitud">Latitud:</label>
                        <input type="text" name="latitud" class="form-control" value="{{ $old->latitud }}" required>
                        <x-input-error :messages="$errors->get('latitud')" />
                    </div>

                    <div class="form-group">
                        <label for="longitud">Longitud:</label>
                        <input type="text" name="logitud" class="form-control" value="{{ $old->logitud }}" required>
                        <x-input-error :messages="$errors->get('logitud')" />
                    </div>

                    <div class="form-group">
                        <label for="Iurl">Iurl:</label>
                        <input type="url" name="Iurl" class="form-control" value="{{ $old->Iurl }}">
                        <x-input-error :messages="$errors->get('Iurl')" />
                    </div>

                    <div class="form-group">
                        <label for="Furl">Furl:</label>
                        <input type="url" name="Furl" class="form-control" value="{{ $old->Furl }}">
                        <x-input-error :messages="$errors->get('Furl')" />
                    </div>

                    <div class="form-group">
                        <label for="Turl">Turl:</label>
                        <input type="url" name="Turl" class="form-control" value="{{ $old->Turl }}">
                        <x-input-error :messages="$errors->get('Turl')" />
                    </div>

                    <div class="form-group">
                        <label for="Xurl">Xurl:</label>
                        <input type="url" name="Xurl" class="form-control" value="{{ $old->Xurl }}">
                        <x-input-error :messages="$errors->get('Xurl')" />
                    </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</x-app-layout>