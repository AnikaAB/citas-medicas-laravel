<?php

namespace Tests\Feature;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class PacienteCitaTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_el_paciente_puede_ver_sus_propias_citas(): void
    {
        $paciente = $this->crearPacienteConUsuario();
        $doctor = $this->crearDoctorConUsuario();

        Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Dolor de cabeza',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($paciente->user)->get('/mis-citas');

        $response->assertStatus(200);
        $response->assertSee('Dolor de cabeza');
    }

    public function test_el_paciente_puede_agendar_una_cita_en_un_horario_libre(): void
    {
        $paciente = $this->crearPacienteConUsuario();
        $doctor = $this->crearDoctorConUsuario(['horario_inicio' => '08:00:00', 'horario_fin' => '17:00:00']);

        $response = $this->actingAs($paciente->user)->post('/mis-citas', [
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '08:00',
            'motivo' => 'Consulta general',
        ]);

        $response->assertRedirect(route('paciente.citas.index'));
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);
    }

    public function test_el_paciente_no_puede_agendar_en_un_horario_ya_ocupado(): void
    {
        $paciente = $this->crearPacienteConUsuario();
        $doctor = $this->crearDoctorConUsuario(['horario_inicio' => '08:00:00', 'horario_fin' => '17:00:00']);
        $fecha = now()->addDay()->toDateString();

        Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '08:00:00',
            'motivo' => 'Cita existente',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->from('/mis-citas/nueva')->actingAs($paciente->user)->post('/mis-citas', [
            'doctor_id' => $doctor->id,
            'fecha' => $fecha,
            'hora' => '08:00',
            'motivo' => 'Quiero el mismo horario',
        ]);

        $response->assertSessionHasErrors('hora');
        $this->assertDatabaseCount('citas', 1);
    }

    public function test_el_paciente_puede_cancelar_su_propia_cita(): void
    {
        $paciente = $this->crearPacienteConUsuario();
        $doctor = $this->crearDoctorConUsuario();

        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($paciente->user)->patch("/mis-citas/{$cita->id}/cancelar");

        $response->assertRedirect(route('paciente.citas.index'));
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado' => Cita::ESTADO_CANCELADA,
        ]);
    }

    public function test_el_paciente_no_puede_cancelar_una_cita_de_otro_paciente(): void
    {
        $paciente = $this->crearPacienteConUsuario(['cedula' => '0700000001']);
        $otroPaciente = $this->crearPacienteConUsuario(['cedula' => '0700000002']);
        $doctor = $this->crearDoctorConUsuario();

        $citaAjena = Cita::create([
            'paciente_id' => $otroPaciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Consulta general',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($paciente->user)->patch("/mis-citas/{$citaAjena->id}/cancelar");

        $response->assertForbidden();
        $this->assertDatabaseHas('citas', [
            'id' => $citaAjena->id,
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);
    }
}
