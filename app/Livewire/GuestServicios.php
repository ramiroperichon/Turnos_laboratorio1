<?php

namespace App\Livewire;

use App\Models\Servicio;
use Livewire\Component;

class GuestServicios extends Component
{
    public $itemAmmount = 8;
    public $search = '';

    protected $listeners = ['search-services' => 'searchUpdated'];

    public function searchUpdated($query)
    {
        $this->search = strtolower($query);
    }

    public function loadMore()
    {
        $this->itemAmmount += 8;
    }

    public function render()
    {
        $query = Servicio::query();

            $query->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('descripcion', 'like', '%' . $this->search . '%');


        return view('livewire.guest-servicios', [
            'servicios' => $query->where('habilitado', true)->cursorPaginate($this->itemAmmount)
        ]);
    }
}
