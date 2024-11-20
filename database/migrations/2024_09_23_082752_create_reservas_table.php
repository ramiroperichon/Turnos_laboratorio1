<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->enum('estado', ['Confirmado', 'Cancelado', 'Pendiente', 'Completado']);
            $table->date('fecha_reserva');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('reservas');
    }
};
