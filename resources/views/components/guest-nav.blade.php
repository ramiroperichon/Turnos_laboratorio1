<nav class="navbar p-0 fixed-top d-flex flex-row left-0">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="/"><img src="{{ asset('assets/images/logo-mini.svg') }}"
                alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch gap-2">
        <div class="d-none d-lg-flex align-items-center justify-content-center">
            <a class="" href="/"><img class="w-44 h-44" src="{{ asset('assets/images/logo.svg') }}"
                    alt="logo" /></a>
        </div>
        <ul class="navbar-nav w-100">
            <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 m-lg-3 m-0 d-flex search">
                    <div x-data="{ query: '', timer: null }" class="flex-grow-1">
                        <input type="text" class="form-control" placeholder="Buscar servicios..." x-model="query"
                            @input="
                                clearTimeout(timer);
                                timer = setTimeout(() => $dispatch('search-services', {'query' : $event.target.value}), 300);
                            ">
                    </div>
                </form>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <a href="/login" class="flex text-center w-fit h-fit items-center">
                <i class="mdi mdi-login fs-3 m-0"></i>
                <p class="m-0 text-center">{{ __('Login') }}</p>
            </a>
        </ul>
    </div>
</nav>
