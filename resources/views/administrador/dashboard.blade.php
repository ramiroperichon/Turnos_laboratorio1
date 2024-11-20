<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard administrador') }}
        </h2>
    </x-slot>
    <div class="page-header">
        <h3 class="page-title"> Panel de administrador </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">Inicio</a></li>
            </ol>
        </nav>
    </div>
    <div class="row" style="min-height: calc(100vh - 100px);">
        <div class="col-xl-8 grid-margin stretch-card justify-content-center">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-row items-center pb-3">
                        <div class="flex-col item-center">
                            <div class="icon icon-box-success size-11 me-2">
                                <span class="mdi mdi mdi-calendar-multiple icon-item"></span>
                            </div>
                        </div>
                        <div class="flex-col">
                            <h4 class="card-title text-start m-0">Turnos pendientes</h4>
                        </div>
                    </div>
                    <div id='calendar'></div>
                    <div class="flex justify-start mt-3">
                        <span class="text-muted text-sm me-2"><span
                                class="rounded-full bg-success px-2 me-1"></span>Confirmado</span>
                        <span class="text-muted text-sm me-2"><span
                                class="rounded-full bg-info px-2 me-1"></span>Pendiente</span>
                        <span class="text-muted text-sm me-2"><span
                                class="rounded-full bg-danger px-2 me-1"></span>Cancelado</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-row items-center pb-3">
                        <div class="flex-none">
                            <div class="icon icon-box-primary size-11 me-2">
                                <span class="mdi mdi mdi mdi-calendar-clock icon-item"></span>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h4 class="card-title mb-1 m-0">Reservas a confirmar</h4>
                        </div>
                        <div class="flex-none ml-auto">
                            <p class="text-muted mb-1 m-0">Estado</p>
                        </div>
                    </div>
                    @foreach ($reservas->where('estado', '=', 'Pendiente')->take(5) as $reserva)
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
                                                    <p class="text-muted mt-2 mb-1">{{ $reserva->fecha_reserva }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="d-flex flex-row justify-content-between items-center my-2">
                        <p class="text-muted mb-1">Reservas pendientes: <span
                                class="font-weight-bold text-info">{{ $reservas->where('estado', '=', 'Pendiente')->count() }}</span>
                        </p>
                        <a class="btn btn-info" href="{{ route('reserva.index') }}">Ver todas las reservas</a>
                    </div>
                </div>
            </div>
        </div>
        <livewire:reserva-detail-modal />
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var eventsj = @json($events);
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {


                eventClick: function(info) {
                    var eventObj = info.event;

                    if (eventObj.url) {
                        alert(
                            'Clicked ' + eventObj.title + '.\n' +
                            'Will open ' + eventObj.url + ' in a new tab'
                        );

                        window.open(eventObj.url);

                        info.jsEvent
                            .preventDefault();
                    } else {
                        Livewire.dispatch('call-livewire-method', { id: eventObj.id });
                    }
                },


                locale: 'es',
                slotEventOverlap: false,
                allDaySlot: false,
                noEventsContent: "No hay reservas en esta fecha",
                aspectRatio: 2,
                initialView: 'listWeek',
                expandRows: false,

                events: eventsj,
                slotDuration: '00:10:00',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    listWeek: 'Lista'
                }
            });
            calendar.render();
        });
    </script>

</x-app-layout>
