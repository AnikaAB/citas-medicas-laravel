<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Se quita el unique compuesto (doctor_id, fecha, hora) a nivel de BD.
     *
     * Motivo: una cita cancelada no debe bloquear ese horario para una
     * cita nueva. La validacion de disponibilidad ahora vive solo en
     * CitaController (store/update), donde se excluyen explicitamente
     * las citas con estado = 'cancelada'. Mantener el unique en BD
     * seguia bloqueando el slot aunque la cita estuviera cancelada.
     */
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropUnique(['doctor_id', 'fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->unique(['doctor_id', 'fecha', 'hora']);
        });
    }
};
