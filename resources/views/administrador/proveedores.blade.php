<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrar proveedores') }}
        </h1>

        <h4>Crear proveedor</h4>
        <form action="{{ route('administrador.crearProveedor') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Crear proveedor</button>
        </form>
        <hr>
        <hr>
        <h2>Proveedores</h2>
        <table>
            <thead>
                <tr class='mg-5'>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>servicio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <div id="modalModificarUsuario"
                    class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Modificar proveedor</h3>
                        <form id="formModificarUsuario" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300" for="name">Nombre</label>
                                <input type="text" name="name" id="name"
                                    class="w-full p-2 border border-gray-300 rounded">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300" for="email">Email</label>
                                <input type="email" name="email" id="email"
                                    class="w-full p-2 border border-gray-300 rounded">
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="cerrarModal()"
                                    class="px-4 py-2 bg-gray-600 text-white rounded mr-2">Cancelar</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

            </tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->servicio ? $usuario->servicio->count() : 'no hay servicio'}}</td>
                    <td>
                        <button type="button"
                            onclick="abrirModal({{ $usuario->id }}, '{{ $usuario->name }}', '{{ $usuario->email }}')"
                            class="px-4 py-2 bg-yellow-500 text-white rounded">Modificar</button>
                        <a href="{{ route('administrador.editarServicios', $usuario->id) }}"
                            class="px-4 py-2 bg-green-500 text-white rounded">Editar servicios</a>
                    </td>

                </tr>
            @endforeach

        </table>

        <script>
            function abrirModal(id, name, email) {

                document.getElementById('modalModificarUsuario').classList.remove('hidden');

                document.getElementById('user_id').value = id;
                document.getElementById('name').value = name;
                document.getElementById('email').value = email;

                document.getElementById('formModificarUsuario').action = `/administrador/modificarProveedor/${id}`;
            }

            function cerrarModal() {
                document.getElementById('modalModificarUsuario').classList.add('hidden');
            }
        </script>

</x-app-layout>
