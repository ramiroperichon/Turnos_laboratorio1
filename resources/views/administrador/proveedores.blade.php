<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administrar proveedores') }}
        </h1>
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

        </table>
        <livewire:usuarios></livewire:usuarios>

</x-app-layout>
