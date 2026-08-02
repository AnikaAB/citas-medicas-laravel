<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PacienteCitaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RecepcionistaController;
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
    // Recepcionistas: modulo propio y completo, exclusivo para admin.
    Route::middleware('rol:admin')->group(function () {
        Route::get('/recepcionistas', [RecepcionistaController::class, 'index'])->name('recepcionistas.index');
        Route::get('/recepcionistas/nueva', [RecepcionistaController::class, 'create'])->name('recepcionistas.create');
        Route::post('/recepcionistas', [RecepcionistaController::class, 'store'])->name('recepcionistas.store');
        Route::get('/recepcionistas/{recepcionista}/editar', [RecepcionistaController::class, 'edit'])->name('recepcionistas.edit');
        Route::put('/recepcionistas/{recepcionista}', [RecepcionistaController::class, 'update'])->name('recepcionistas.update');
        Route::delete('/recepcionistas/{recepcionista}', [RecepcionistaController::class, 'destroy'])->name('recepcionistas.destroy');
        Route::patch('/recepcionistas/{recepcionista}/activar', [RecepcionistaController::class, 'activar'])->name('recepcionistas.activar');
    });

    // Usuarios: modulo basico de gestion, solo para admin.
    Route::middleware('rol:admin')->group(function () {
        Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
        Route::patch('/usuarios/{usuario}/estado', [\App\Http\Controllers\UserController::class, 'alternarEstado'])->name('usuarios.alternarEstado');
    });

    // Autogestion del paciente: ver/agendar/cancelar SUS propias citas
    // y editar su propio perfil. Nunca gestiona citas ni pacientes ajenos.
    Route::middleware('rol:paciente')->group(function () {
        Route::get('/mis-citas', [PacienteCitaController::class, 'index'])->name('paciente.citas.index');
        Route::get('/mis-citas/nueva', [PacienteCitaController::class, 'create'])->name('paciente.citas.create');
        Route::post('/mis-citas', [PacienteCitaController::class, 'store'])->name('paciente.citas.store');
        Route::get('/mis-citas/{cita}/reprogramar', [PacienteCitaController::class, 'editReprogramar'])->name('paciente.citas.reprogramar');
        Route::put('/mis-citas/{cita}/reprogramar', [PacienteCitaController::class, 'reprogramar'])->name('paciente.citas.reprogramar.update');
        Route::patch('/mis-citas/{cita}/cancelar', [PacienteCitaController::class, 'cancelar'])->name('paciente.citas.cancelar');
        // Endpoints AJAX que alimentan los <select> dependientes del formulario de agendar.
        Route::get('/mis-citas/doctores-por-especialidad', [PacienteCitaController::class, 'doctoresPorEspecialidad'])->name('paciente.citas.doctores');
        Route::get('/mis-citas/horarios-disponibles', [PacienteCitaController::class, 'horariosDisponibles'])->name('paciente.citas.horarios');

        Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    });
});