<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('especialidad');
            $table->string('telefono', 20);
            $table->string('email')->unique();
            $table->time('horario_inicio')->default('08:00:00');
            $table->time('horario_fin')->default('17:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctores');
    }
};
