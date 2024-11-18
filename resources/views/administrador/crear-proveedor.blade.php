<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear proveedor') }}
        </h2>
    </x-slot>

    <div class="page-header">
        <h3 class="page-title"> Administrar proveedor </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('administrador.proveedores') }}">Proveedores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear proveedor</li>
            </ol>
        </nav>
    </div>

    <div class="m-2">
        <form action="{{ route('administrador.storeProveedor') }}" method="POST">
            @csrf
            @method('POST')
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        {{ __('Crear proveedor') }}
                    </h4>

                    <p class="card-description">
                        {{ __('Ingrese los datos del proveedor, incluyendo informaciÃ³n de contacto, redes sociales y numero telefonico.') }}
                    </p>
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div class="form-group">
                        <label for="last_name">{{ __('Apellido') }}</label>
                        <input id="last_name" name="last_name" type="text"
                            class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}"
                            required autofocus autocomplete="last_name" />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            required autocomplete="username">
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="form-group">
                        <label for="phone">{{ __('Numero de telefono') }}</label>
                        <input id="phone" name="phone" type="text"
                            class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                            required required autocomplete="tel" pattern="[0-9]{10}"
                            title="De 10 a 15 caracteres sin el (15) ej: 3777323313">
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div class="form-group">
                        <label for="profesion">{{ __('Profesion') }}</label>
                        <input id="profesion" name="profesion" type="text"
                            class="form-control @error('profesion') is-invalid @enderror"
                            value="{{ old('profesion') }}" required autocomplete="profesion">
                        <x-input-error class="mt-2" :messages="$errors->get('profesion')" />
                    </div>

                    <div class="form-group">
                        <label for="horario_inicio">{{ __('Inicio de jornada') }}</label>
                        <input id="horario_inicio" name="horario_inicio" type="time"
                            class="form-control @error('horario_inicio') is-invalid @enderror"
                            value="{{ old('horario_inicio') }}" required autocomplete="horario_inicio">
                        <x-input-error class="mt-2" :messages="$errors->get('horario_inicio')" />
                    </div>

                    <div class="form-group">
                        <label for="horario_fin">{{ __('Fin de jornada') }}</label>
                        <input id="horario_fin" name="horario_fin" type="time"
                            class="form-control @error('horario_fin') is-invalid @enderror"
                            value="{{ old('horario_fin') }}" required autocomplete="horario_fin">
                        <x-input-error class="mt-2" :messages="$errors->get('horario_fin')" />
                    </div>

                    <div class="form-group">
                        <label>{{ __('Password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>
                    <div class="form-group">
                        <label>{{ __('Confirm Password') }}</label>
                        <input type="password" class="form-control @error('new-password') is-invalid @enderror"
                            type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <button class="btn btn-primary fs-5 font-weight-bold"
                            type="submit">{{ __('Save') }}</button>
                        <a class="btn btn-dark fs-5" href="{{ route('administrador.proveedores') }}">Volver</a>
                    </div>
                </div>
            </div>
        </form>
</x-app-layout>
