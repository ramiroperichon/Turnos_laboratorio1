<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        </h1>
    </x-slot>

    <h2>Servicios</h2>
    @foreach ($servicios as $servicio)
        <tr>
            <td>{{ $servicio->id ?? null }}</td>
            <td>{{ $servicio->nombre }}</td>
            <td class="text-truncate text-wrap" style="max-width: 30px;">{{ $servicio->descripcion }}</td>
            <td>${{ $servicio->precio }}</td>
            <td>{{ \Carbon\Carbon::parse($servicio->inicio_turno)->format('H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($servicio->fin_turno)->format('H:i') }}</td>
            <td>{{ $servicio->duracion }} Min.</td>
            <td>{{ $servicio->dias_disponible }}</td>
        </t>
    @endforeach
    <td colspan="8" class="text-center">
        <h4 class="text-muted">No hay servicios</h4>
    </td>

</x-app-layout>
