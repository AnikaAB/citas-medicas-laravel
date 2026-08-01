<?php

namespace Tests\Feature;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class ControlAccesoTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_un_invitado_es_redirigido_al_login_si_intenta_entrar_al_panel(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_un_doctor_no_puede_acceder_a_la_gestion_de_pacientes(): void
    {
        $doctor = $this->crearDoctorConUsuario();

        $response = $this->actingAs($doctor->user)->get('/pacientes');

        $response->assertForbidden();
    }

    public function test_una_recepcionista_no_puede_acceder_a_la_gestion_de_doctores(): void
    {
        $recepcionista = $this->crearRecepcionista();

        $response = $this->actingAs($recepcionista)->get('/doctores');

        $response->assertForbidden();
    }

    public function test_un_paciente_no_puede_acceder_al_listado_administrativo_de_citas(): void
    {
        $paciente = $this->crearPacienteConUsuario();

        $response = $this->actingAs($paciente->user)->get('/citas');

        $response->assertForbidden();
    }

    public function test_un_doctor_solo_ve_sus_propias_citas_en_su_listado(): void
    {
        $doctorPropio = $this->crearDoctorConUsuario([
            'email' => 'propio@clinica.com',
            'nombre' => 'Ricardo',
            'apellido' => 'Alvarado',
        ]);
        $doctorAjeno = $this->crearDoctorConUsuario([
            'email' => 'ajeno@clinica.com',
            'nombre' => 'Sofia',
            'apellido' => 'Mendoza',
        ]);
        $paciente = $this->crearPacienteConUsuario();

        $citaPropia = Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctorPropio->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'motivo' => 'Cita del doctor propio',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $citaAjena = Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctorAjeno->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00:00',
            'motivo' => 'Cita del doctor ajeno',
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        $response = $this->actingAs($doctorPropio->user)->get('/citas');

        // La tabla de citas no imprime "motivo", asi que la señal valida
        // es el nombre del doctor de cada fila: solo debe aparecer el propio.
        $response->assertStatus(200);
        $response->assertSee('Dr. '.$doctorPropio->nombre.' '.$doctorPropio->apellido);
        $response->assertDontSee('Dr. '.$doctorAjeno->nombre.' '.$doctorAjeno->apellido);

        // Ademas, no puede ver el detalle de la cita ajena directamente por URL.
        $this->actingAs($doctorPropio->user)
            ->get("/citas/{$citaAjena->id}")
            ->assertForbidden();
    }
}
