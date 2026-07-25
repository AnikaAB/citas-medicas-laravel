<?php

namespace Tests\Unit;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas UNITARIAS del modelo Cita: relaciones y valores por defecto.
 */
class CitaModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_cita_pertenece_a_un_paciente_y_a_un_doctor(): void
    {
        $paciente = Paciente::factory()->create();
        $doctor = Doctor::factory()->create();
        $cita = Cita::factory()->create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
        ]);

        $this->assertTrue($cita->paciente->is($paciente));
        $this->assertTrue($cita->doctor->is($doctor));
    }

    public function test_el_estado_por_defecto_de_una_nueva_cita_es_pendiente(): void
    {
        $cita = Cita::factory()->create();

        $this->assertSame(Cita::ESTADO_PENDIENTE, $cita->estado);
    }

    public function test_la_fecha_se_castea_a_instancia_de_fecha(): void
    {
        $cita = Cita::factory()->create(['fecha' => '2026-08-15']);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $cita->fecha);
    }
}
