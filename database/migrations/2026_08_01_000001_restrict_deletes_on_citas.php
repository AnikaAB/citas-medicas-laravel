<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cambia el borrado en cascada de citas.doctor_id y citas.paciente_id
     * por RESTRICT.
     *
     * Motivo: antes, borrar un doctor o un paciente borraba en cascada
     * TODAS sus citas (incluido el historial de citas ya atendidas),
     * incluso si alguien lo hacia por fuera de los controladores (tinker,
     * un script, otra herramienta). Ahora DoctorController::destroy() y
     * PacienteController::destroy() ya bloquean el borrado en la app si
     * hay citas pendientes/confirmadas, pero eso no protege la base de
     * datos si alguien la toca directamente. Con RESTRICT, MySQL/MariaDB
     * rechaza el DELETE de un doctor o paciente mientras tenga citas
     * asociadas (de cualquier estado), reforzando la regla tambien a
     * nivel de BD.
     */
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('paciente_id')
                ->references('id')->on('pacientes')
                ->restrictOnDelete();

            $table->foreign('doctor_id')
                ->references('id')->on('doctores')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('paciente_id')
                ->references('id')->on('pacientes')
                ->cascadeOnDelete();

            $table->foreign('doctor_id')
                ->references('id')->on('doctores')
                ->cascadeOnDelete();
        });
    }
};
