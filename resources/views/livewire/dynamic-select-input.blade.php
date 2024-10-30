<div class="row g-2">
    @foreach ($horarios as $index => $horario)
        <div class="col-lg-2">
            <input type="radio" required id="option{{ $index }}" name="horario_id"
                value="{{ $horario->hora_inicio . ',' . $horario->hora_fin }}" class="btn-check"
                @if ($horario->disponibilidad == 'Reservado') disabled @endif>
            <label class="btn btn-primary btn-rounded w-100" for="option{{ $index }}">
                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
            </label>
        </div>
    @endforeach
    <input id="result" type="date" hidden name="fecha_reserva" wire:model="selectedDate"
        wire:change="updateHorarios($event.target.value)">
</div>
