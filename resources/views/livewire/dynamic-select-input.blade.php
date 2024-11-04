<div class="row g-2">
    @if(empty($horarios))
        <div class="col-12">
            <div class="alert alert-info text-center">
                Seleccione una fecha para ver los horarios disponibles
            </div>
        </div>
    @else
        @foreach ($horarios as $index => $horario)
            <div class="col-lg-2">
                <input type="radio" required id="option{{ $index }}" name="horario_id"
                    value="{{ $horario->hora_inicio . ',' . $horario->hora_fin . ',' . $horario->disponibilidad }}" class="btn-check"
                    @if ($horario->disponibilidad == 'Reservado') disabled @endif>
                <label class="btn btn-primary btn-rounded w-100" for="option{{ $index }}">
                    {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
                </label>
            </div>
        @endforeach
    @endif
    <input id="result" type="date" name="fecha_reserva" wire:model="selectedDate"
        wire:change="updateHorarios($event.target.value)">
</div>
