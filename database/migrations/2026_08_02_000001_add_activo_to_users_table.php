<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega el campo "activo" a users.
     *
     * Permite al admin desactivar una cuenta (ej. un doctor que ya no
     * trabaja en la clinica, un recepcionista dado de baja) sin borrar
     * su historial ni sus relaciones (citas, doctor, paciente). Un
     * usuario inactivo no puede iniciar sesion (ver LoginController).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('rol');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};
