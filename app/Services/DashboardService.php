<?php

namespace App\Services;

class DashboardService
{
    public function mapEvents($events){

        $eventData = [];

        $eventColor = '#fc424a';

        foreach ($events as $event) {
            switch ($event->estado) {
                case 'Confirmado':
                    $eventColor = '#00d25b';
                    break;
                case 'Pendiente':
                    $eventColor = '#8f5fe8';
                    break;
                case 'Cancelado':
                    $eventColor = '#fc424a';
                    break;
                default:
                    $eventColor = '#808080';
                    break;
            }

            if ($event->estado != 'Completado') {
                $eventData[] = [
                    'title' => $event->user->name . ' Turno en ' .
                        $event->servicio->nombre,
                    'start' => $event->fecha_reserva . ' ' . $event->hora_inicio,
                    'end' => $event->fecha_reserva . ' ' . $event->hora_fin,
                    'backgroundColor' => $eventColor,
                    'borderColor' => $eventColor
                ];
            }
        }

        return $eventData;
    }
}
