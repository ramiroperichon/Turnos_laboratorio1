<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ingresar datos de negocio') }}
        </h2>
    </x-slot>
    <div class="m-2">
        <form action="{{ route('administrador.detallenegocio.update', $old->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card ml-25 mr-25 px-3 py-3">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" class="form-control bg-body-secondary" value="{{ $old->nombre }}"
                        required>
                    <x-input-error :messages="$errors->get('nombre')" />
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control bg-body-secondary"
                        value="{{ $old->email }}" required>
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control bg-body-secondary"
                        value="{{ $old->telefono }}" required>
                    <x-input-error :messages="$errors->get('telefono')" />
                </div>

                    <input type="text" id="latitud" name="latitud" hidden class="form-control bg-body-secondary"
                        value="{{ $old->latitud }}" required>
                    <x-input-error :messages="$errors->get('latitud')" />

                    <input type="text" id="logitud" name="logitud" hidden class="form-control bg-body-secondary"
                        value="{{ $old->logitud }}" required>
                    <x-input-error :messages="$errors->get('logitud')" />

                <div class="form-group">
                    <label for="map">Ubicacion:</label>
                <div style="height: 500px;
            width: 100%;" id="map">
                </div>
            </div>

                <div class="form-group">
                    <label for="Iurl">Instagram:</label>
                    <input type="url" name="Iurl" class="form-control bg-body-secondary"
                        value="{{ $old->Iurl }}">
                    <x-input-error :messages="$errors->get('Iurl')" />
                </div>

                <div class="form-group">
                    <label for="Furl">Facebook:</label>
                    <input type="url" name="Furl" class="form-control bg-body-secondary"
                        value="{{ $old->Furl }}">
                    <x-input-error :messages="$errors->get('Furl')" />
                </div>

                <div class="form-group">
                    <label for="Turl">TikTok:</label>
                    <input type="url" name="Turl" class="form-control bg-body-secondary"
                        value="{{ $old->Turl }}">
                    <x-input-error :messages="$errors->get('Turl')" />
                </div>

                <div class="form-group">
                    <label for="Xurl">Twitter(x):</label>
                    <input type="url" name="Xurl" class="form-control bg-body-secondary"
                        value="{{ $old->Xurl }}">
                    <x-input-error :messages="$errors->get('Xurl')" />
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary mt-3" onclick="location.href='{{ route('dashboard') }}'">Cancelar</button>
            </div>
        </form>
    </div>

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

                document.getElementById('logitud').value = clickedLocation.lng();
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
