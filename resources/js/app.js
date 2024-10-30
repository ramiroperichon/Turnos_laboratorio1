import './bootstrap';

import Alpine from 'alpinejs';

import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/tailwind.css'
import TomSelect from "tom-select";
window.TomSelect = TomSelect

window.Alpine = Alpine;

Alpine.start();
