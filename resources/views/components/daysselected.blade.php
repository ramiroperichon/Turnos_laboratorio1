@php
    $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
@endphp

<div class="flex-row">
    <p class="group cursor-pointer font-weight-medium text-muted">
        Dias actuales del servicio:
    </p>
    <div class="flex flex-wrap items-center justify-center">
        @foreach ($days as $day)
                @if (in_array($day, explode(',', $getRecord()->dias_disponible)))
                    <div
                        class="m-0 py-0.5 bg-primary-400 bg-opacity-10 px-1.5 border-2 border-[#4db1ee]/30  text-sm text-primary">
                        {{ $day }}
                    </div>
                @endif
        @endforeach
    </div>
</div>
