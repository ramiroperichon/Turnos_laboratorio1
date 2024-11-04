<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard proveedor') }}
        </h2>
    </x-slot>
    @session('status')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="alert alert-danger alert-dismissible fade show">
            <ul class="p-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row" style="min-height: calc(100vh - 100px);">
        <div class="col-md-8 grid-margin stretch-card justify-content-center">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Turnos pendientes</h4>
                    <div class="flex justify-start">
                        <span class="text-muted text-sm me-2"><span
                                class="rounded-full bg-success px-2 me-1"></span>Confirmado</span>
                        <span class="text-muted text-sm me-2"><span
                                class="rounded-full bg-info px-2 me-1"></span>Pendiente</span>
                        <span class="text-muted text-sm me-2"><span
                                class="rounded-full bg-danger px-2 me-1"></span>Cancelado</span>
                    </div>
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title mb-1">Reservas a confirmar</h4>
                        <p class="text-muted mb-1">Estado</p>
                    </div>
                    @foreach ($reservas->take(5) as $reserva)
                        @if ($reserva->estado == 'Pendiente')
                            <div class="row">
                                <div class="col">
                                    <div class="preview-list">
                                        <div class="preview-item border-bottom items-center">
                                            <div class="preview-thumbnail d-flex">
                                                <form class="my-1 mx-1"
                                                    action="{{ route('reserva.confirmreject', ['reserva' => $reserva->id, 'estado' => 'Confirmado']) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <button class="btn btn-success" aria-label="Confirmar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form class="my-1 mx-1"
                                                    action="{{ route('reserva.confirmreject', ['reserva' => $reserva->id, 'estado' => 'Cancelado']) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <button class="btn btn-danger" aria-label="Cancelar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="preview-item-content d-sm-flex flex-grow">
                                                <div class="flex-grow">
                                                    <h6 class="preview-subject pt-2">{{ $reserva->user->name }}</h6>
                                                    <p class="text-muted mb-0">{{ $reserva->servicio->nombre }}</p>
                                                </div>
                                                <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                    @if ($reserva->estado == 'Pendiente')
                                                        <span
                                                            class="badge badge-outline-info">{{ $reserva->estado }}</span>
                                                    @elseif($reserva->estado == 'Cancelado')
                                                        <span
                                                            class="badge badge-outline-danger">{{ $reserva->estado }}</span>
                                                    @elseif ($reserva->estado == 'Confirmado')
                                                        <span
                                                            class="badge badge-outline-success">{{ $reserva->estado }}</span>
                                                    @else
                                                        <span class="badge badge-success">{{ $reserva->estado }}</span>
                                                    @endif
                                                    <p class="text-muted mt-2 mb-1">{{ $reserva->fecha_reserva }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="d-flex flex-row justify-content-between items-center my-2">
                        <p class="text-muted mb-1">Reservas restantes: <span
                                class="font-weight-bold text-info">{{ $reservas->count() }}</span></span></p>
                        <a class="btn btn-info" href="{{ route('reserva.user') }}">Ver todas las reservas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var eventsj = @json($events);
            var calendarEl = document.getElementById('calendar');
            console.log(eventsj);

            var calendar = new FullCalendar.Calendar(calendarEl, {

                locale: 'es',
                slotEventOverlap: false,
                allDaySlot: false,
                aspectRatio: 2,
                initialView: 'timeGridWeek',
                expandRows: false,

                events: eventsj,
                slotDuration: '00:10:00',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay' // user can switch between the two
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                }
            });
            calendar.render();
        });
    </script>

</x-app-layout>
