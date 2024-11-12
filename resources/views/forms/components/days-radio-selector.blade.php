<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">


    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="btn-group btn-group-sm flex-wrap mt-2" role="group" aria-label="Basic checkbox toggle button group">
            @foreach (['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'] as $day)
                <input type="checkbox" class="btn-check" value="{{ $day }}" x-model="state"
                    id="btncheck{{ $getRecord()->id }}{{ $day }}"
                    @if (in_array($day, explode(',', $getRecord()->dias_disponible))) checked @else @endif>
                <label class="btn btn-outline-info"
                    for="btncheck{{ $getRecord()->id }}{{ $day }}">{{ $day }}</label>
            @endforeach
        </div>
    </div>
</x-dynamic-component>
