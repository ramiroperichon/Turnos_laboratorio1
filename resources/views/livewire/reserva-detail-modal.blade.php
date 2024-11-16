<div>
    @if (isset($reserva))
        <div x-data="{ show: @entangle('isOpen') }">
            <div x-show="show" x-on:close-modal.window="show = false"
                class="fixed self-center inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
                <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="show = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <div x-show="show"
                    class="mb-6 dark:bg-[#191c24] rounded-md overflow-hidden shadow-xl transform transition-all max-w-lg sm:w-full sm:mx-auto"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="row mb-1">
                        <div class="flex items-center justify-between border-b-2 border-gray-600">
                            <div class="flex items-center grow">
                                <div class="flex-none py-2.5 ps-4">
                                    <div class="icon icon-box-info size-11 me-2">
                                        <span class="mdi mdi-calendar-text icon-item"></span>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <h4 class="card-title mb-1 m-0">Reserva n° {{ $reserva->id }}</h4>
                                </div>
                            </div>
                            <div class="flex items-center ml-auto pb-3.5">
                                <button wire:model="isOpen" wire:click="isOpen = false"
                                    class="float-end inline-flex justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                    <span class="sr-only">Cerrar panel</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row px-4 mt-2 mb-2">
                        <div class="flex items-center justify-evenly space-x-8">
                            <div class="flex-col items-center">
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-account m-0 p-0 text-primary"></i><span class="text-white m-0 p-0">{{ "{$reserva->user->name} {$reserva->user->last_name}" }}</span>
                                </h5>
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-briefcase m-0 p-0 text-primary"></i><span class="text-white">{{ $reserva->servicio->nombre }}</span>
                                </h5>
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-calendar m-0 p-0 text-primary"></i><span class="text-white">{{ $reserva->fecha_reserva }}</span>
                                </h5>
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-tooltip-edit m-0 p-0 text-primary"></i><span class="text-white">{{ $reserva->estado }}</span>
                                </h5>
                            </div>
                            <div class="flex-col">
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-cellphone-basic m-0 p-0 text-primary"></i><span class="text-white">{{ $reserva->user->phone }}</span>
                                </h5>
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-account-star m-0 p-0 text-primary"></i><span class="text-white">{{ "{$reserva->servicio->proveedor->name} {$reserva->servicio->proveedor->last_name}" }}</span>
                                </h5>
                                <h5 class="font-medium text-muted fs-5 flex gap-2">
                                    <i class="mdi mdi-timelapse m-0 p-0 text-primary"></i><span class="text-white">{{ Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }}</span>
                                    a
                                    <span class="text-white">{{ Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</span>
                                </h5>
                                <h5 class="font-medium fs-5 flex gap-2 text-success">
                                    <i class="mdi mdi-currency-usd m-0 p-0"></i>{{ $reserva->servicio->precio }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row px-4 py-1 mt-1 mb-1 border-t-2 border-gray-600">
                        <div class="flex items-center px-4 py-2 justify-center gap-4">
                            @if ($reserva->estado == 'Pendiente')
                                <x-tooltip-arrow text="Confirmar reserva" position="top">
                                    <button class="btn btn-success" wire:click="updateReserva('Confirmado')"
                                        wire:loading.attr="disabled" wire:target="updateReserva">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" wire:loading.remove.delay
                                            wire:target="updateReserva('Confirmado')">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <div class="border-gray-300 size-4 animate-spin rounded-full border-2 border-t-purple-500"
                                            wire:loading.delay wire:target="updateReserva('Confirmado')"></div>
                                    </button>
                                </x-tooltip-arrow>
                                <x-tooltip-arrow text="Cancelar reserva" position="top">
                                    <button class="btn btn-danger" wire:click="updateReserva('Cancelado')"
                                        wire:loading.attr="disabled" wire:target="updateReserva">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" wire:loading.remove.delay
                                            wire:target="updateReserva('Cancelado')">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <div class="border-gray-300 size-4 animate-spin rounded-full border-2 border-t-purple-500"
                                            wire:loading.delay wire:target="updateReserva('Cancelado')"></div>
                                    </button>
                                </x-tooltip-arrow>
                            @else
                                <button class="btn btn-dark px-3 py-2 fs-6" wire:click="isOpen = false">
                                    Ok
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
