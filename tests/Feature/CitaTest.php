<?php

namespace Tests\Feature;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class CitaTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_admin_puede_ver_el_listado_de_citas(): void
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
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($admin)->get('/citas');

        // La tabla de citas no imprime "motivo": se verifica con el
        // nombre del paciente, que si se muestra en cada fila.
        $response->assertStatus(200);
        $response->assertSee($paciente->nombre);
    }

    public function test_recepcionista_puede_registrar_una_cita_nueva(): void
    {
        $recepcionista = $this->crearRecepcionista();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        $response = $this->actingAs($recepcionista)->post('/citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Control periodico',
            'estado' => 'pendiente',
        ]);

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'motivo' => 'Control periodico',
        ]);
    }

    public function test_no_se_puede_agendar_dos_citas_para_el_mismo_doctor_en_la_misma_fecha_y_hora(): void
    {
        $recepcionista = $this->crearRecepcionista();
        $doctor = $this->crearDoctorConUsuario();
        $paciente1 = $this->crearPacienteConUsuario(['cedula' => '0700000001']);
        $paciente2 = $this->crearPacienteConUsuario(['cedula' => '0700000002']);

        $fecha = now()->addDays(2)->toDateString();

        Cita::create([
            'paciente_id' => $paciente1->id,
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '10:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->from('/citas/create')->actingAs($recepcionista)->post('/citas', [
            'paciente_id' => $paciente2->id,
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '10:00',
            'motivo' => 'Otra consulta',
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors('hora');
        $this->assertDatabaseCount('citas', 1);
    }

    public function test_admin_puede_editar_una_cita_existente(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($admin)->put("/citas/{$cita->id}", [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '11:00',
            'motivo' => 'Consulta reprogramada',
        ]);

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'motivo' => 'Consulta reprogramada',
        ]);
    }

    public function test_no_se_puede_eliminar_una_cita_ya_atendida(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->subDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_ATENDIDA,
        ]);

        $response = $this->actingAs($admin)->delete("/citas/{$cita->id}");

        $response->assertSessionHasErrors('estado');
        $this->assertDatabaseHas('citas', ['id' => $cita->id]);
    }
}
