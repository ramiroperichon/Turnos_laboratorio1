<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            {{ __('Profile Information') }}
        </h4>

        <p class="card-description">
            {{ __("Update your account's profile information and email address.") }}
        </p>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input id="name" name="name" type="text"
                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="form-group">
                <label for="last_name">{{ __('Apellido') }}</label>
                <input id="last_name" name="last_name" type="text"
                    class="form-control @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name', $user->last_name) }}" required autofocus autocomplete="last_name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" name="email" type="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"
                    required autocomplete="username">
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="phone">{{ __('Numero de telefono') }}</label>
                <input id="phone" name="phone" type="text"
                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}"
                    required required autocomplete="tel" pattern="[0-9]{10}" title="De 10 a 15 caracteres sin el (15) ej: 3777323313">
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            @if ($user->proveedor && $user->proveedor->profesion != null)
                <div class="form-group">
                    <label for="profesion">{{ __('Profesion') }}</label>
                    <input id="profesion" name="profesion" type="text"
                        class="form-control @error('profesion') is-invalid @enderror"
                        value="{{ old('profesion', $user->proveedor->profesion) }}" required autocomplete="profesion">
                    <x-input-error class="mt-2" :messages="$errors->get('profesion')" />
                </div>

                <div class="form-group">
                    <label for="horario_inicio">{{ __('Inicio de jornada') }}</label>
                    <input id="horario_inicio" name="horario_inicio" type="time"
                        class="form-control @error('horario_inicio') is-invalid @enderror"
                        value="{{ old('horario_inicio', \Carbon\Carbon::parse($user->proveedor->horario_inicio)->format('H:i')) }}"
                        required autocomplete="horario_inicio">
                    <x-input-error class="mt-2" :messages="$errors->get('horario_inicio')" />
                </div>

                <div class="form-group">
                    <label for="horario_fin">{{ __('Fin de jornada') }}</label>
                    <input id="horario_fin" name="horario_fin" type="time"
                        class="form-control @error('horario_fin') is-invalid @enderror"
                        value="{{ old('horario_fin', \Carbon\Carbon::parse($user->proveedor->horario_fin)->format('H:i')) }}"
                        required autocomplete="horario_fin">
                    <x-input-error class="mt-2" :messages="$errors->get('horario_fin')" />
                </div>
            @else
                <div class="form-group">
                    <label for="documento">{{ __('Documento') }}</label>
                    <input id="documento" name="documento" type="text"
                        class="form-control @error('documento') is-invalid @enderror"
                        value="{{ old('documento', $user->cliente->documento) }}" required autocomplete="documento">
                    <x-input-error class="mt-2" :messages="$errors->get('documento')" />
                </div>
            @endif

            <div class="flex items-center gap-4">
                <button class="btn btn-primary fs-5 font-weight-bold" type="submit">{{ __('Save') }}</button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</div>
