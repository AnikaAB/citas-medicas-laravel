<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->constrained('pacientes')->restrictOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctores')->restrictOnDelete();

            $table->foreignId('creado_por')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->date('fecha');
            $table->time('hora');
            $table->string('motivo');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'atendida'])
                ->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Nota: NO se define UNIQUE(doctor_id, fecha, hora) a nivel de BD.
            // La regla "un doctor no puede tener dos citas activas en la misma
            // fecha/hora" se valida solo en la aplicacion (CitaController),
            // donde se excluyen las citas con estado = 'cancelada'.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
