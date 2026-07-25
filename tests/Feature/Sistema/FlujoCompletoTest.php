<?php

namespace Tests\Feature\Sistema;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Prueba de SISTEMA (end-to-end): simula el flujo real de negocio
 * completo, de punta a punta, tal como lo haria un usuario real.
 */
class FlujoCompletoTest extends TestCase
{
    use RefreshDatabase;

    public function test_flujo_completo_registro_login_y_agendamiento_de_cita(): void
    {
        // 1. Un visitante se registra como paciente
        $this->post(route('register.store'), [
            'nombre' => 'Laura',
            'apellido' => 'Mendez',
            'cedula' => '0709998877',
            'telefono' => '0987651234',
            'fecha_nacimiento' => '1992-04-15',
            'direccion' => 'Sector Norte',
            'email' => 'laura@correo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        // 2. El paciente inicia sesion normalmente
        $this->post(route('login.attempt'), [
            'email' => 'laura@correo.com',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        // 3. Un recepcionista agenda una cita para ese paciente
        $recepcionista = User::factory()->create(['rol' => User::ROL_RECEPCIONISTA]);
        $doctor = Doctor::factory()->create(['especialidad' => 'Medicina General']);
        $paciente = \App\Models\Paciente::where('cedula', '0709998877')->firstOrFail();

        $this->actingAs($recepcionista)->post(route('citas.store'), [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDays(5)->toDateString(),
            'hora' => '09:00',
            'motivo' => 'Chequeo general',
            'estado' => 'pendiente',
        ])->assertRedirect(route('citas.index'));

        // 4. La cita queda visible en el listado con el paciente correcto
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'motivo' => 'Chequeo general',
        ]);

        // 5. Un usuario sin permisos (paciente) no puede acceder al listado administrativo de citas
        $usuarioPaciente = User::where('email', 'laura@correo.com')->firstOrFail();
        $this->actingAs($usuarioPaciente)->get(route('citas.index'))->assertForbidden();

        // 6. Pero si puede ver su propio panel con su cita agendada
        $this->actingAs($usuarioPaciente)->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('totalCitas', 1);
    }
}
