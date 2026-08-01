<?php

namespace Tests\Feature;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class PacienteTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_admin_puede_ver_el_listado_de_pacientes(): void
    {
        $admin = $this->crearAdmin();
        $paciente = $this->crearPacienteConUsuario(['nombre' => 'Roberto', 'apellido' => 'Cruz']);

        $response = $this->actingAs($admin)->get('/pacientes');

        $response->assertStatus(200);
        $response->assertSee('Roberto');
    }

    public function test_admin_puede_registrar_un_paciente_nuevo(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post('/pacientes', [
            'nombre' => 'Luis',
            'apellido' => 'Martinez',
            'cedula' => '0700001111',
            'telefono' => '0987001122',
            'email' => 'lmartinez@correo.com',
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Guayaquil',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', ['cedula' => '0700001111']);
        $this->assertDatabaseHas('users', ['email' => 'lmartinez@correo.com', 'rol' => 'paciente']);
    }

    public function test_no_se_puede_registrar_un_paciente_con_cedula_duplicada(): void
    {
        $admin = $this->crearAdmin();
        $this->crearPacienteConUsuario(['cedula' => '0700001111']);

        $response = $this->from('/pacientes/create')->actingAs($admin)->post('/pacientes', [
            'nombre' => 'Luis',
            'apellido' => 'Martinez',
            'cedula' => '0700001111',
            'telefono' => '0987001122',
            'email' => 'otro@correo.com',
            'fecha_nacimiento' => '1990-01-01',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('cedula');
        $this->assertDatabaseCount('pacientes', 1);
    }

    public function test_admin_puede_actualizar_los_datos_de_un_paciente(): void
    {
        $admin = $this->crearAdmin();
        $paciente = $this->crearPacienteConUsuario(['telefono' => '0987654321']);

        $response = $this->actingAs($admin)->put("/pacientes/{$paciente->id}", [
            'nombre' => $paciente->nombre,
            'apellido' => $paciente->apellido,
            'cedula' => $paciente->cedula,
            'telefono' => '0991112233',
            'email' => $paciente->email,
            'fecha_nacimiento' => $paciente->fecha_nacimiento->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', [
            'id' => $paciente->id,
            'telefono' => '0991112233',
        ]);
    }

    public function test_no_se_puede_eliminar_un_paciente_con_citas_activas(): void
    {
        $admin = $this->crearAdmin();
        $paciente = $this->crearPacienteConUsuario();
        $doctor = $this->crearDoctorConUsuario();

        Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($admin)->delete("/pacientes/{$paciente->id}");

        $response->assertSessionHasErrors('paciente');
        $this->assertDatabaseHas('pacientes', ['id' => $paciente->id]);
    }
}
