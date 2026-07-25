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
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctores')->cascadeOnDelete();
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha');
            $table->time('hora');
            $table->string('motivo');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'atendida'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Un mismo doctor no puede tener dos citas a la misma fecha/hora
            $table->unique(['doctor_id', 'fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
