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
                        class="bg-primary-400 bg-opacity-10 px-2 py-1 mx-1 border-2 font-medium border-[#4db1ee]/30 rounded-md text-sm text-primary-400">
                        {{ $day }}
                    </div>
                @endif
        @endforeach
    </div>
</div>
