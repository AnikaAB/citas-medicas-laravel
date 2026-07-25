<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla propia para "olvide mi contraseña".
     * Guarda un codigo de 6 digitos por correo, con expiracion,
     * en vez de depender del sistema de notificaciones/correo de
     * Laravel (el proyecto no tiene un servidor SMTP real configurado).
     */
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('codigo', 6);
            $table->timestamp('expira_en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
