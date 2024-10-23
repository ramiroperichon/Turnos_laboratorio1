<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_negocios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', length: 50);
            $table->string('email', length: 50);
            $table->string('telefono', length: 15);
            $table->decimal('latitud', total: 18, places: 16);
            $table->decimal('logitud', total: 18, places: 16);
            $table->mediumText('Iurl')->nullable();
            $table->mediumText('Furl')->nullable();
            $table->mediumText('Turl')->nullable();
            $table->mediumText('Xurl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_negocios');
    }
};
