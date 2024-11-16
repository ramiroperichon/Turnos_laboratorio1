<div class="row">
    @forelse($servicios as $servicio)
        <div x-init="$el.style.opacity = '0';
        $el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            $el.style.transition = 'all 0.5s ease';
            $el.style.opacity = '1';
            $el.style.transform = 'translateY(0)';
        }, $el.dataset.index * 50)" data-index="{{ $loop->index }}" class="col-md-4 col-lg-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-row">
                        <div class="grow">
                            <h3 class="card-title mb-1">{{ $servicio->nombre }}</h3>
                            <p class="text-muted mb-1 fs-6">{{ $servicio->descripcion }}</p>
                        </div>
                        <div class="basis-1/4 grow-0">
                            <i class="icon-lg mdi mdi-wallet-travel text-muted ml-auto"></i>
                        </div>
                    </div>
                    <p class="text-body-secondary fs-5 mb-1 text-green-500 flex items-center">
                        <i class="mdi mdi-currency-usd text-success"></i>
                        {{ $servicio->precio }}
                    </p>
                    <p class="text-body-secondary mb-1 flex gap-2">
                        <i class="mdi mdi mdi-alarm m-0 p-0 fs-5 text-info"></i>
                        <span class="text-white m-0 p-0 fs-5">
                            {{ Carbon\Carbon::parse($servicio->incio_turno)->format('H:i') }} a
                            {{ Carbon\Carbon::parse($servicio->fin_turno)->format('H:i') }}
                        </span>
                    </p>
                    <p class="text-body-secondary mb-1 flex gap-2">
                        <i class="mdi mdi-calendar-clock m-0 p-0 fs-5 text-info"></i>
                        <span class="text-white m-0 p-0 fs-5">
                            @php
                                $dias = explode(',', $servicio->dias_disponible);
                            @endphp
                            @if (count($dias) > 1)
                                {{ $dias[0] }} a {{ $dias[count($dias) - 1] }}
                            @else
                                {{ $dias[0] }}
                            @endif
                        </span>
                    </p>
                    <p class="text-body-secondary flex fs-6 item-center">
                        <span class="rounded-sm bg-gray-dark m-0 p-1 items-center">
                            <i class="mdi mdi-timelapse m-0 p-1 text-white"></i>
                            {{ $servicio->duracion }} minutos
                        </span>
                    </p>
                    <div class="d-flex justify-content-between align-items-center self-end">
                        <a href="{{ route('reserva.user', $servicio->id) }}" class="btn btn-primary btn-block">Reservar
                            turno</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-4">
                        @if ($search)
                            <p class="fs-4">No se encontraron servicios que coincidan con "{{ $search }}"</p>
                            <button wire:click="$set('search', '')" class="btn btn-dark mt-2 fs-5">
                                <i class="mdi mdi-reload"></i>
                                Mostrar todos los servicios
                            </button>
                        @else
                            <p>No hay servicios disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforelse
    <div x-intersect.threshold.30="$wire.loadMore()"></div>
</div>
