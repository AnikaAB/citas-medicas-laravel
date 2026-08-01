<?php

namespace Tests\Feature;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_admin_puede_ver_el_listado_de_doctores(): void
    {
        $admin = $this->crearAdmin();
        $this->crearDoctorConUsuario(['nombre' => 'Sofia', 'apellido' => 'Mendoza']);

        $response = $this->actingAs($admin)->get('/doctores');

        $response->assertStatus(200);
        $response->assertSee('Sofia');
    }

    public function test_admin_puede_registrar_un_doctor_nuevo(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post('/doctores', [
            'nombre' => 'Miguel',
            'apellido' => 'Torres',
            'especialidad' => 'Cardiologia',
            'telefono' => '0991234567',
            'email' => 'mtorres@clinica.com',
            'horario_inicio' => '08:00',
            'horario_fin' => '17:00',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('doctores.index'));
        $this->assertDatabaseHas('doctores', ['email' => 'mtorres@clinica.com']);
        $this->assertDatabaseHas('users', ['email' => 'mtorres@clinica.com', 'rol' => 'doctor']);
    }

    public function test_no_se_puede_registrar_un_doctor_con_horario_fin_antes_del_inicio(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->from('/doctores/create')->actingAs($admin)->post('/doctores', [
            'nombre' => 'Miguel',
            'apellido' => 'Torres',
            'especialidad' => 'Cardiologia',
            'telefono' => '0991234567',
            'email' => 'mtorres@clinica.com',
            'horario_inicio' => '17:00',
            'horario_fin' => '08:00',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('horario_fin');
        $this->assertDatabaseCount('doctores', 0);
    }

    public function test_admin_puede_actualizar_los_datos_de_un_doctor(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario(['especialidad' => 'Medicina General']);

        $response = $this->actingAs($admin)->put("/doctores/{$doctor->id}", [
            'nombre' => $doctor->nombre,
            'apellido' => $doctor->apellido,
            'especialidad' => 'Dermatologia',
            'telefono' => $doctor->telefono,
            'email' => $doctor->email,
            'horario_inicio' => '08:00',
            'horario_fin' => '17:00',
        ]);

        $response->assertRedirect(route('doctores.index'));
        $this->assertDatabaseHas('doctores', [
            'id' => $doctor->id,
            'especialidad' => 'Dermatologia',
        ]);
    }

    public function test_no_se_puede_eliminar_un_doctor_con_citas_activas(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_CONFIRMADA,
        ]);

        $response = $this->actingAs($admin)->delete("/doctores/{$doctor->id}");

        $response->assertSessionHasErrors('doctor');
        $this->assertDatabaseHas('doctores', ['id' => $doctor->id]);
    }
}
