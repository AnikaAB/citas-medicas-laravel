<?php

namespace Tests\Feature\Validacion;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de VALIDACION: entradas invalidas y reglas de negocio de citas.
 */
class CitaValidacionTest extends TestCase
{
    use RefreshDatabase;

    private function actor(): User
    {
        return User::factory()->create(['rol' => User::ROL_RECEPCIONISTA]);
    }

    public function test_no_permite_crear_una_cita_sin_campos_obligatorios(): void
    {
        $response = $this->actingAs($this->actor())->post(route('citas.store'), []);

        $response->assertSessionHasErrors([
            'paciente_id', 'doctor_id', 'fecha', 'hora', 'motivo', 'estado',
        ]);
        $this->assertDatabaseCount('citas', 0);
    }

    public function test_no_permite_una_fecha_en_el_pasado(): void
    {
        $paciente = Paciente::factory()->create();
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->actor())->post(route('citas.store'), [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->subDay()->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Consulta',
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors('fecha');
    }

    public function test_no_permite_un_paciente_o_doctor_inexistente(): void
    {
        $response = $this->actingAs($this->actor())->post(route('citas.store'), [
            'paciente_id' => 999,
            'doctor_id' => 999,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Consulta',
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors(['paciente_id', 'doctor_id']);
    }

    public function test_no_permite_un_estado_fuera_del_catalogo_permitido(): void
    {
        $paciente = Paciente::factory()->create();
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->actor())->post(route('citas.store'), [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Consulta',
            'estado' => 'inventado',
        ]);

        $response->assertSessionHasErrors('estado');
    }

    /**
     * Regla de negocio clave: un doctor no puede tener dos citas
     * en la misma fecha y hora (choque de agenda).
     */
    public function test_no_permite_dos_citas_para_el_mismo_doctor_en_la_misma_fecha_y_hora(): void
    {
        $doctor = Doctor::factory()->create();
        $existente = Cita::factory()->create([
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '09:00',
        ]);
        $otroPaciente = Paciente::factory()->create();

        $response = $this->actingAs($this->actor())->post(route('citas.store'), [
            'paciente_id' => $otroPaciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => $existente->fecha->toDateString(),
            'hora' => '09:00',
            'motivo' => 'Otra consulta',
            'estado' => 'pendiente',
        ]);

        $response->assertSessionHasErrors('hora');
        $this->assertDatabaseCount('citas', 1);
    }
}
