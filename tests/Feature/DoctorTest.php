<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas FUNCIONALES del CRUD completo de doctores.
 */
class DoctorTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['rol' => User::ROL_ADMIN]);
    }

    public function test_lista_los_doctores_registrados(): void
    {
        Doctor::factory()->count(2)->create();

        $response = $this->actingAs($this->admin())->get(route('doctores.index'));

        $response->assertOk();
        $response->assertViewHas('doctores', fn ($d) => $d->total() === 2);
    }

    public function test_crea_un_doctor_junto_con_su_usuario_de_acceso(): void
    {
        $response = $this->actingAs($this->admin())->post(route('doctores.store'), [
            'nombre' => 'Elena',
            'apellido' => 'Vaca',
            'especialidad' => 'Traumatologia',
            'telefono' => '0991112233',
            'email' => 'evaca@clinica.com',
            'horario_inicio' => '08:00',
            'horario_fin' => '16:00',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('doctores.index'));
        $this->assertDatabaseHas('doctores', ['email' => 'evaca@clinica.com']);
        $this->assertDatabaseHas('users', ['email' => 'evaca@clinica.com', 'rol' => 'doctor']);
    }

    public function test_no_permite_horario_fin_anterior_o_igual_al_horario_inicio(): void
    {
        $response = $this->actingAs($this->admin())->post(route('doctores.store'), [
            'nombre' => 'Elena',
            'apellido' => 'Vaca',
            'especialidad' => 'Traumatologia',
            'telefono' => '0991112233',
            'email' => 'evaca@clinica.com',
            'horario_inicio' => '16:00',
            'horario_fin' => '08:00',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('horario_fin');
    }

    public function test_actualiza_los_datos_de_un_doctor(): void
    {
        $doctor = Doctor::factory()->create(['especialidad' => 'Medicina General']);

        $response = $this->actingAs($this->admin())->put(route('doctores.update', $doctor), [
            'nombre' => $doctor->nombre,
            'apellido' => $doctor->apellido,
            'especialidad' => 'Neurologia',
            'telefono' => $doctor->telefono,
            'email' => $doctor->email,
            'horario_inicio' => '08:00',
            'horario_fin' => '17:00',
        ]);

        $response->assertRedirect(route('doctores.index'));
        $this->assertDatabaseHas('doctores', ['id' => $doctor->id, 'especialidad' => 'Neurologia']);
    }

    public function test_elimina_un_doctor(): void
    {
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->admin())->delete(route('doctores.destroy', $doctor));

        $response->assertRedirect(route('doctores.index'));
        $this->assertDatabaseMissing('doctores', ['id' => $doctor->id]);
    }
}
