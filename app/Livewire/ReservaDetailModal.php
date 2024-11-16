<?php

namespace App\Livewire;

use App\Models\Reserva;
use App\Services\ServicioService;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class ReservaDetailModal extends Component
{

    public $reservaID = 1;
    public $isOpen = false;
    public $loadingButton = null;
    protected $listeners = ['call-livewire-method' => 'someMethod'];

    public function someMethod($id)
    {
        $this->reservaID = $id;
        $this->isOpen = true;
        $this->dispatch('openModal', 'confirm-user-deletion');
    }

    public function confirmReserva(){

    }

    public function updateReserva($estado){
        $this->loadingButton = $estado;
        $service = new ServicioService;
        $reserva = Reserva::find($this->reservaID);
        $service->updateReserva($reserva, $estado);
        $this->isOpen = false;
        $this->loadingButton = null;
        Toaster::success('La reserva ha sido ' . $estado);
        $this->redirectRoute('dashboard');
    }

    public function render()
    {
        if ($this->reservaID) {
            $reserva = Reserva::find($this->reservaID);
            return view('livewire.reserva-detail-modal', [
                'reserva' => $reserva,
            ]);
        }
        return view('livewire.reserva-detail-modal');
    }
}
