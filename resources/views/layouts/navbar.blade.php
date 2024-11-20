<nav class="navbar p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="/"><img src="{{ asset('assets/images/logo-mini.svg') }}"
                alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        @if(Auth::check())
        <ul class="navbar-nav navbar-nav-right">
            @hasrole('proveedor')
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link btn btn-success create-new-button" href="{{ route('servicio.create') }}"
                        id="createbuttonDropdown" aria-expanded="false">+ Crear un nuevo servicio</a>
                </li>
            @endhasrole
            <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                    <div class="navbar-profile">
                        <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face15.jpg') }}"
                            alt="">
                        <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ Auth::user()->name }}</p>
                        <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="profileDropdown">
                    @hasanyrole(['cliente', 'proveedor'])
                    <h6 class="p-3 mb-0">{{__('Profile')}}</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item" href="{{ route('profile.edit') }}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">{{__('Settings')}}</p>
                        </div>
                    </a>
                    @endhasanyrole
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item preview-item" :href="route('logout')"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-logout text-danger"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">{{__('Logout')}}</p>
                            </div>
                        </a>
                    </form>
                </div>
            </li>
        </ul>
        @endif
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>
