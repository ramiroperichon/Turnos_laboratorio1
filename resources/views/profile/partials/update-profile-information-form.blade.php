<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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

        @if ($user->proveedor && $user->proveedor->profesion != null)
            <div>
                <x-input-label for="profesion" :value="__('Profesion')" />
                <x-text-input id="profesion" name="profesion" type="text" class="mt-1 block w-full" :value="old('profesion', $user->proveedor->profesion)" required autocomplete="profesion" />
                <x-input-error class="mt-2" :messages="$errors->get('profesion')" />
            </div>
        
            <div>
                <x-input-label for="horario_inicio" :value="__('Horario Inicio')" />
                <x-text-input id="horario_inicio" name="horario_inicio" type="time" class="mt-1 block w-full" :value="old('horario_inicio', $user->proveedor->horario_inicio)" required autocomplete="horario_inicio" />
                <x-input-error class="mt-2" :messages="$errors->get('horario_inicio')" />
            </div>
        
            <div>
                <x-input-label for="horario_fin" :value="__('Horario Fin')" />
                <x-text-input id="horario_fin" name="horario_fin" type="time" class="mt-1 block w-full" :value="old('horario_fin', $user->proveedor->horario_fin)" required autocomplete="horario_fin" />
                <x-input-error class="mt-2" :messages="$errors->get('horario_fin')" />
            </div>
        @elseif ($user->cliente && $user->cliente->documento != null)
            <div>
                <x-input-label for="documento" :value="__('Documento')" />
                <x-text-input id="documento" name="documento" type="text" class="mt-1 block w-full" :value="old('documento', $user->cliente->documento)" required autocomplete="documento" />
                <x-input-error class="mt-2" :messages="$errors->get('documento')" />
            </div>
        @endif
        
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
