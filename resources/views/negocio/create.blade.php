<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>
    </x-slot>
    <form action="{{ route('detalleNegocio.update', $old->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="card ml-25 mr-25">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->nombre }}" required>
                <x-input-error :messages="$errors->get('nombre')" />
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->email }}" required>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->telefono }}" required>
                <x-input-error :messages="$errors->get('telefono')" />
            </div>

            <div class="form-group">
                <label for="latitud">Latitud:</label>
                <input type="text" id="latitud" name="latitud" class="form-control bg-body-secondary"
                    value="{{ $old->latitud }}" required>
                <x-input-error :messages="$errors->get('latitud')" />
            </div>

            <div class="form-group">
                <label for="longitud">Longitud:</label>
                <input type="text" id="longitud" name="logitud" class="form-control bg-body-secondary"
                    value="{{ $old->logitud }}" required>
                <x-input-error :messages="$errors->get('logitud')" />
            </div>

            <div style="height: 500px;
            width: 100%;" id="map">
            </div>

            <div class="form-group">
                <label for="Iurl">Iurl:</label>
                <input type="url" name="Iurl" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->Iurl }}">
                <x-input-error :messages="$errors->get('Iurl')" />
            </div>

            <div class="form-group">
                <label for="Furl">Furl:</label>
                <input type="url" name="Furl" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->Furl }}">
                <x-input-error :messages="$errors->get('Furl')" />
            </div>

            <div class="form-group">
                <label for="Turl">Turl:</label>
                <input type="url" name="Turl" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->Turl }}">
                <x-input-error :messages="$errors->get('Turl')" />
            </div>

            <div class="form-group">
                <label for="Xurl">Xurl:</label>
                <input type="url" name="Xurl" class="form-control col-sm-5 bg-body-secondary"
                    value="{{ $old->Xurl }}">
                <x-input-error :messages="$errors->get('Xurl')" />
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <script>
        let map;
        let marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: -29.14383470536903,
                    lng: -59.26513140291266
                },
                zoom: 16,
                mapId: 'map'
            });

            google.maps.event.addListener(map, 'click', function(event) {
                const clickedLocation = event.latLng;

                document.getElementById('longitud').value = clickedLocation.lng();
                document.getElementById('latitud').value = clickedLocation.lat();
                if (marker) {
                    marker.position = clickedLocation;
                } else {
                    marker = new google.maps.marker.AdvancedMarkerElement({
                        position: clickedLocation,
                        map: map
                    });
                }
            });
        }
    </script>
</x-app-layout>
