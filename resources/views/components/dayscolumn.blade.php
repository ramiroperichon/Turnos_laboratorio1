@php
    $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
@endphp

<div class="flex-row">
    <p class="group cursor-pointer font-weight-medium text-muted">
        Dias disponibles:
    </p>
    <div class="flex flex-wrap gap-2 items-center justify-center">
        @foreach ($days as $day)
            <div class="group/algo cursor-pointer">
                @if (in_array($day, explode(',', $getRecord()->dias_disponible)))
                    <div
                        class="rounded-sm py-0.5 bg-transparent px-1.5 border border-info hover:bg-[#b18fef] hover:text-white text-sm text-info transition-all ease-in-out shadow-sm hover:shadow-inner">
                        {{ substr($day, 0, 2) }}
                    </div>
                @endif
                <div
                    class="absolute scale-0 invisible z-50 whitespace-normal break-words rounded-lg bg-[#191c24] py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none transition-all duration-200 group-hover/algo:visible group-hover/algo:opacity-100 group-hover/algo:scale-100">
                    {{ $day }}
                </div>
            </div>
        @endforeach
    </div>
</div>
