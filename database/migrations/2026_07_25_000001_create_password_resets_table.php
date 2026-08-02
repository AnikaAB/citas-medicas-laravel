<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Flujo de "olvide mi contraseña" por codigo de 6 digitos, valido por
     * 15 minutos, en vez de depender de un servidor SMTP real.
     * Requerida por App\Http\Controllers\Auth\PasswordResetController.
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
