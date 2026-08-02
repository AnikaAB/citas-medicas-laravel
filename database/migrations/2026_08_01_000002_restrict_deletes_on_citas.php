<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 5 del plan de correccion.
 *
 * La migracion original de "citas" usaba cascadeOnDelete() en doctor_id y
 * paciente_id: si alguien borraba un doctor o un paciente (por ejemplo por
 * fuera del controlador, con un seeder, tinker, o un acceso directo a la
 * BD), se borraba en cascada TODO su historial de citas sin dejar rastro.
 *
 * Los controladores (DoctorController@destroy, PacienteController@destroy)
 * ya bloquean el borrado si hay citas pendientes/confirmadas, pero eso solo
 * protege el camino que pasa por la app. Esta migracion cambia la llave
 * foranea a restrictOnDelete() para que la base de datos tambien impida
 * borrar un doctor/paciente que todavia tenga citas asociadas, sin
 * importar por donde se intente el borrado.
 *
 * Nota: SQLite (usado en los tests, ver phpunit.xml) define las llaves
 * foraneas dentro del propio CREATE TABLE y no permite modificarlas con un
 * ALTER TABLE simple sin reconstruir la tabla completa. Como en los tests
 * las FK de SQLite no se validan (no se activa PRAGMA foreign_keys), la
 * proteccion real para ese entorno ya la dan los controladores; aqui la
 * migracion solo actua sobre MySQL/PostgreSQL (produccion), que es donde
 * sí hace falta el respaldo a nivel de base de datos.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['paciente_id']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('doctor_id')->references('id')->on('doctores')->restrictOnDelete();
            $table->foreign('paciente_id')->references('id')->on('pacientes')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['paciente_id']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('doctor_id')->references('id')->on('doctores')->cascadeOnDelete();
            $table->foreign('paciente_id')->references('id')->on('pacientes')->cascadeOnDelete();
        });
    }
};
