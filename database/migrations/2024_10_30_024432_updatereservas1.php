<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign(['horario_id']);
            $table->dropColumn('horario_id');

            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('observaciones')->nullable();
            $table->boolean('habilitado')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');

            $table->dropColumn('hora_inicio');
            $table->dropColumn('hora_fin');
            $table->dropColumn('observaciones');
            $table->dropColumn('habilitado');
        });
    }
};

