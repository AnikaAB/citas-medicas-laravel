<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas FUNCIONALES del CRUD completo de citas (create, read, update, delete).
 */
class CitaTest extends TestCase
{
    use RefreshDatabase;

    private function actor(string $rol = User::ROL_RECEPCIONISTA): User
    {
        return User::factory()->create(['rol' => $rol]);
    }

    public function test_lista_las_citas_registradas(): void
    {
        Cita::factory()->count(3)->create();

        $response = $this->actingAs($this->actor())->get(route('citas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('citas.index');
        $response->assertViewHas('citas', fn ($citas) => $citas->total() === 3);
    }

    public function test_crea_una_cita_correctamente(): void
    {
        $paciente = Paciente::factory()->create();
        $doctor = Doctor::factory()->create();
        $usuario = $this->actor();

        $response = $this->actingAs($usuario)->post(route('citas.store'), [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '10:00',
            'motivo' => 'Consulta general',
            'estado' => 'pendiente',
            'observaciones' => null,
        ]);

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'motivo' => 'Consulta general',
            'creado_por' => $usuario->id,
        ]);
    }

    public function test_muestra_el_detalle_de_una_cita(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->actingAs($this->actor())->get(route('citas.show', $cita));

        $response->assertStatus(200);
        $response->assertViewHas('cita', fn ($c) => $c->id === $cita->id);
    }

    public function test_actualiza_una_cita_existente(): void
    {
        $cita = Cita::factory()->create(['motivo' => 'Motivo original']);

        $response = $this->actingAs($this->actor())->put(route('citas.update', $cita), [
            'paciente_id' => $cita->paciente_id,
            'doctor_id' => $cita->doctor_id,
            'fecha' => $cita->fecha->toDateString(),
            'hora' => '11:00',
            'motivo' => 'Motivo actualizado',
            'estado' => 'confirmada',
            'observaciones' => 'Paciente confirmo asistencia',
        ]);

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'motivo' => 'Motivo actualizado',
            'estado' => 'confirmada',
        ]);
    }

    public function test_elimina_una_cita(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->actingAs($this->actor())->delete(route('citas.destroy', $cita));

        $response->assertRedirect(route('citas.index'));
        $this->assertDatabaseMissing('citas', ['id' => $cita->id]);
    }

    public function test_filtra_citas_por_estado(): void
    {
        Cita::factory()->create(['estado' => 'pendiente']);
        Cita::factory()->confirmada()->create();

        $response = $this->actingAs($this->actor())->get(route('citas.index', ['estado' => 'confirmada']));

        $response->assertStatus(200);
        $response->assertViewHas('citas', fn ($citas) => $citas->total() === 1);
    }
}
