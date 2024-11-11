<div class="row g-2 mt-2">
    @if (empty($horarios))
        <div class="col-12">
            <div class="alert alert-info text-center">
                Seleccione una fecha para ver los horarios disponibles
            </div>
        </div>
    @else
        <div class="d-flex flex-wrap justify-content-start gap-3 flex-grow-1">
            @foreach ($horarios as $index => $horario)
                <div class="flex mb-2">
                    <input type="radio" required id="option{{ $index }}" name="horario_id"
                        value="{{ $horario->hora_inicio . ',' . $horario->hora_fin . ',' . $horario->disponibilidad }}"
                        class="btn-check" @if ($horario->disponibilidad == 'Reservado') disabled @endif>
                    <label class="btn btn-primary btn-rounded px-2.5" for="option{{ $index }}">
                        {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
                    </label>
                </div>
            @endforeach
        </div>
    @endif
    <input id="result" type="date" hidden name="fecha_reserva" wire:model="selectedDate"
        wire:change="updateHorarios($event.target.value)">
</div>
