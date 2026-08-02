<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MisCitasController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

// --- Autenticacion ---
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'mostrarLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'mostrarRegistro'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store')->middleware('guest');

// --- Recuperar contraseña (3 pasos: correo -> codigo -> nueva contraseña) ---
Route::middleware('guest')->group(function () {
    Route::get('/password/olvide', [PasswordResetController::class, 'mostrarSolicitud'])->name('password.olvide');
    Route::post('/password/olvide', [PasswordResetController::class, 'enviarCodigo'])->name('password.enviar');
    Route::get('/password/codigo', [PasswordResetController::class, 'mostrarCodigo'])->name('password.codigo');
    Route::post('/password/codigo', [PasswordResetController::class, 'verificarCodigo'])->name('password.verificar');
    Route::get('/password/restablecer', [PasswordResetController::class, 'mostrarRestablecer'])->name('password.restablecer');
    Route::post('/password/restablecer', [PasswordResetController::class, 'restablecer'])->name('password.restablecer.guardar');
});

// --- Rutas protegidas (requieren sesion iniciada) ---
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Citas.
    // Lectura (index/show): admin, recepcionista y doctor (el doctor solo ve
    // su propia agenda, filtrado dentro del controlador).
    // Escritura (create/store/edit/update/destroy): SOLO admin y recepcionista.
    // Antes esto era un Route::resource() unico abierto a los 3 roles, lo
    // que permitia a un doctor editar/eliminar citas ajenas escribiendo la
    // URL directamente (el controlador no revisaba el rol en esas acciones).
    Route::middleware('rol:admin,recepcionista')->group(function () {
        Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
        Route::get('/citas/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
        Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
        Route::patch('/citas/{cita}', [CitaController::class, 'update']);
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
    });

    Route::middleware('rol:admin,recepcionista,doctor')->group(function () {
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
        Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
    });

    // Pacientes: gestion completa solo para admin y recepcionista
    Route::middleware('rol:admin,recepcionista')->group(function () {
        Route::resource('pacientes', PacienteController::class);
    });

    // Doctores: gestion completa solo para admin
    Route::middleware('rol:admin')->group(function () {
        Route::resource('doctores', DoctorController::class)->parameters(['doctores' => 'doctor']);
    });
    // Usuarios: modulo basico de gestion, solo para admin.
    Route::middleware('rol:admin')->group(function () {
        Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
        Route::patch('/usuarios/{usuario}/estado', [\App\Http\Controllers\UserController::class, 'alternarEstado'])->name('usuarios.alternarEstado');
    });

    // Mis Citas: exclusivo para el paciente, solo sobre SUS PROPIAS citas.
    // Puede agendar, ver su historial, cancelar y reprogramar (con 24h+
    // de anticipacion, regla validada en el controlador y en el modelo).
    Route::middleware('rol:paciente')->group(function () {
        Route::get('/mis-citas', [MisCitasController::class, 'index'])->name('mis-citas.index');
        Route::get('/mis-citas/agendar', [MisCitasController::class, 'create'])->name('mis-citas.create');
        Route::post('/mis-citas/agendar', [MisCitasController::class, 'store'])->name('mis-citas.store');
        Route::get('/mis-citas/{cita}/reprogramar', [MisCitasController::class, 'editReprogramar'])->name('mis-citas.reprogramar');
        Route::put('/mis-citas/{cita}/reprogramar', [MisCitasController::class, 'reprogramar'])->name('mis-citas.reprogramar.update');
        Route::patch('/mis-citas/{cita}/cancelar', [MisCitasController::class, 'cancelar'])->name('mis-citas.cancelar');
    });
});
