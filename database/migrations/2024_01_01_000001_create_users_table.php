<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('rol', ['admin', 'recepcionista', 'doctor', 'paciente'])
                ->default('paciente')
                ->comment('Rol del usuario dentro del sistema');
            $table->boolean('activo')
                ->default(true)
                ->comment('Si es 0, el usuario no puede iniciar sesion (cuenta desactivada por el admin)');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
