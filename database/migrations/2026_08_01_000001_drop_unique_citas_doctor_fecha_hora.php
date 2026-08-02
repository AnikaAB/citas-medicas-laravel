<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 4 del plan de correccion.
 *
 * El unique(doctor_id, fecha, hora) original bloqueaba un horario aunque la
 * cita que lo ocupaba estuviera cancelada, porque a nivel de base de datos
 * no distingue el estado. La app ya excluye las citas canceladas al validar
 * choques de horario (CitaController@store / @update), pero mientras el
 * indice siga existiendo, la base de datos puede rechazar un INSERT/UPDATE
 * valido con un error de integridad en vez de dejar que la app decida.
 *
 * Se opta por la opcion (a) del documento de correccion: quitar el unique
 * de BD y confiar solo en la validacion de la aplicacion, que es la que
 * conoce la regla de negocio real ("no cancelada" bloquea, "cancelada" no).
 *
 * IMPORTANTE: en MySQL, el indice unique(doctor_id, fecha, hora) es el que
 * respalda la llave foranea de "doctor_id" (por ser el unico indice que
 * empieza con esa columna). Si se intenta borrar el unique directamente,
 * MySQL lo rechaza con el error 1553 "needed in a foreign key constraint".
 * Por eso primero se crea un indice normal (no unico) sobre doctor_id, y
 * recien despues se puede eliminar el unique compuesto sin romper la FK.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->index('doctor_id');
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->dropUnique(['doctor_id', 'fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->unique(['doctor_id', 'fecha', 'hora']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->dropIndex(['doctor_id']);
        });
    }
};
