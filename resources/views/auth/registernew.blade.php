<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="row w-100 m-0">
                <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
                    <div class="card col-lg-4 mx-auto">
                        <div class="card-body px-5 py-5">
                            <h3 class="card-title text-left mb-3">{{ __('Register') }}</h3>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label>{{ __('Name') }}</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" type="text"
                                        name="name" value="{{ old('name') }}" placeholder="Ingrese su nombre"
                                        required autofocus autocomplete="name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Apellido') }}</label>
                                    <input id="last_name" type="text"
                                        class="form-control @error('last_name') is-invalid @enderror" type="text"
                                        name="last_name" value="{{ old('last_name') }}"
                                        placeholder="Ingrese su apellido" required autofocus autocomplete="last_name" />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Email') }}</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" value="{{ old('email') }}" placeholder="Ingrese su email"
                                        required autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                </div>
                                <div class="form-group">
                                    <label for="phone">{{ __('Numero de telefono') }}</label>
                                    <input id="phone" name="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" required required autocomplete="tel"
                                        placeholder="Ingrese el numero de telefono"
                                        pattern="[0-9]{10}" title="De 10 a 15 caracteres sin el (15) ej: 3777323313">
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div class="form-group">
                                    <label for="documento">{{ __('Documento') }}</label>
                                    <input id="documento" name="documento" type="text"
                                        class="form-control @error('documento') is-invalid @enderror"
                                        value="{{ old('documento') }}" required
                                        placeholder="Ingrese su numero de documento"
                                        autocomplete="documento">
                                    <x-input-error class="mt-2" :messages="$errors->get('documento')" />
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Ingrese su contraseña"
                                        type="password" name="password" minlength="8" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Confirm Password') }}</label>
                                    <input type="password"
                                        class="form-control @error('new-password') is-invalid @enderror" type="password"
                                        placeholder="Confirme su contraseña"
                                        name="password_confirmation" minlength="8" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                </div>

                                <div class="text-center">
                                    <x-primary-button
                                        class="btn btn-primary btn-block enter-btn">{{ __('Register') }}</x-primary-button>
                                </div>
                                <p class="sign-up text-center">Already have an Account?<a class="mx-1"
                                        href="{{ route('login') }}">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- row ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/misc.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/todolist.js"></script>
</body>

</html>
