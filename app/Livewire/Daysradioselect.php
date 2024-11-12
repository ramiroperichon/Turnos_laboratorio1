<?php

namespace App\Livewire;

use Livewire\Component;

class Daysradioselect extends Component
{

    public $dias_disponible;

    protected $rules = [
        'dias_disponible' => 'required', // add validation if needed
    ];

    public function submit()
    {
        $this->validate();

        // Use $this->dias_disponible as needed
    }

    public function render()
    {
        return view('livewire.daysradioselect');
    }
}
