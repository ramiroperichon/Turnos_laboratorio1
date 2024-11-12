<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>
    </x-slot>

    <div class="m-2">
        <form action="{{ route('administrador.storeProveedor') }}" method="POST">
            @csrf
            @method('POST')
            <div class="card ml-25 mr-25 px-3 py-3">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="name" class="form-control bg-body-secondary" value="" required>
                    <x-input-error :messages="$errors->get('nombre')" />
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control bg-body-secondary" value="" required>
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control bg-body-secondary" value=""
                        required>
                    <x-input-error :messages="$errors->get('telefono')" />
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <x-text-input type="password" class="form-control p_input" type="password" name="password" required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>
                <div class="form-group">
                    <label>Confirm password</label>
                    <x-text-input type="password" class="form-control p_input" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>
            </div>
    </div>

    </div>
    <div>
        <x-input-label for="profesion" :value="__('Profesion')" />
        <x-text-input id="profesion" name="profesion" type="text" class="mt-1 block w-full" required
            autocomplete="profesion" />
        <x-input-error class="mt-2" :messages="$errors->get('profesion')" />
    </div>

    <div>
        <x-input-label for="horario_inicio" :value="__('Horario Inicio')" />
        <x-text-input id="horario_inicio" name="horario_inicio" type="time" class="mt-1 block w-full" required
            autocomplete="horario_inicio" />
        <x-input-error class="mt-2" :messages="$errors->get('horario_inicio')" />
    </div>

    <div>
        <x-input-label for="horario_fin" :value="__('Horario Fin')" />
        <x-text-input id="horario_fin" name="horario_fin" type="time" class="mt-1 block w-full" required
            autocomplete="horario_fin" />
        <x-input-error class="mt-2" :messages="$errors->get('horario_fin')" />
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary mt-3"
        onclick="location.href='{{ route('dashboard') }}'">Cancelar</button>
    </form>
</x-app-layout>
