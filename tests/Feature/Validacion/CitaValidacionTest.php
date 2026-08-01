<?php

namespace Tests\Feature\Validacion;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class CitaValidacionTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_no_se_puede_crear_una_cita_sin_los_campos_obligatorios(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->from('/citas/create')->actingAs($admin)->post('/citas', []);

        $response->assertSessionHasErrors([
            'paciente_id', 'doctor_id', 'fecha', 'hora', 'motivo', 'estado',
        ]);
    }

    public function test_no_se_puede_agendar_una_cita_en_una_fecha_pasada(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        $response = $this->from('/citas/create')->actingAs($admin)->post('/citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->subDay()->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Consulta general',
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors('fecha');
    }

    public function test_la_hora_debe_tener_un_formato_valido(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        $response = $this->from('/citas/create')->actingAs($admin)->post('/citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '25:99',
            'motivo' => 'Consulta general',
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors('hora');
    }

    public function test_el_estado_debe_ser_uno_de_los_valores_permitidos(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente = $this->crearPacienteConUsuario();

        $response = $this->from('/citas/create')->actingAs($admin)->post('/citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Consulta general',
            'estado' => 'inventado',
        ]);

        $response->assertSessionHasErrors('estado');
    }

    public function test_una_cita_cancelada_libera_el_horario_para_una_cita_nueva(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();
        $paciente1 = $this->crearPacienteConUsuario(['cedula' => '0700000001']);
        $paciente2 = $this->crearPacienteConUsuario(['cedula' => '0700000002']);

        $fecha = now()->addDays(2)->toDateString();

        Cita::create([
            'paciente_id' => $paciente1->id,
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '10:00:00',
            'motivo' => 'Consulta cancelada',
            'estado' => Cita::ESTADO_CANCELADA,
        ]);

        $response = $this->actingAs($admin)->post('/citas', [
            'paciente_id' => $paciente2->id,
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '10:00',
            'motivo' => 'Nueva consulta',
            'estado' => 'pendiente',
        ]);

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseCount('citas', 2);
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $paciente2->id,
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);
    }
}
