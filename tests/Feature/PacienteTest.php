<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas FUNCIONALES del CRUD completo de pacientes.
 */
class PacienteTest extends TestCase
{
    use RefreshDatabase;

    private function actor(): User
    {
        return User::factory()->create(['rol' => User::ROL_RECEPCIONISTA]);
    }

    public function test_lista_los_pacientes_registrados(): void
    {
        Paciente::factory()->count(2)->create();

        $response = $this->actingAs($this->actor())->get(route('pacientes.index'));

        $response->assertOk();
        $response->assertViewHas('pacientes', fn ($p) => $p->total() === 2);
    }

    public function test_crea_un_paciente_junto_con_su_usuario_de_acceso(): void
    {
        $response = $this->actingAs($this->actor())->post(route('pacientes.store'), [
            'nombre' => 'Karla',
            'apellido' => 'Zambrano',
            'cedula' => '0711122233',
            'telefono' => '0991112233',
            'email' => 'karla@correo.com',
            'fecha_nacimiento' => '1994-01-01',
            'direccion' => 'Centro',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', ['cedula' => '0711122233']);
        $this->assertDatabaseHas('users', ['email' => 'karla@correo.com', 'rol' => 'paciente']);
    }

    public function test_actualiza_los_datos_de_un_paciente(): void
    {
        $paciente = Paciente::factory()->create(['telefono' => '0900000000']);

        $response = $this->actingAs($this->actor())->put(route('pacientes.update', $paciente), [
            'nombre' => $paciente->nombre,
            'apellido' => $paciente->apellido,
            'cedula' => $paciente->cedula,
            'telefono' => '0999999999',
            'email' => $paciente->email,
            'fecha_nacimiento' => $paciente->fecha_nacimiento->toDateString(),
            'direccion' => 'Nueva direccion',
        ]);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', ['id' => $paciente->id, 'telefono' => '0999999999']);
    }

    public function test_elimina_un_paciente(): void
    {
        $paciente = Paciente::factory()->create();

        $response = $this->actingAs($this->actor())->delete(route('pacientes.destroy', $paciente));

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseMissing('pacientes', ['id' => $paciente->id]);
    }

    public function test_no_permite_crear_paciente_con_cedula_duplicada(): void
    {
        Paciente::factory()->create(['cedula' => '0711122233']);

        $response = $this->actingAs($this->actor())->post(route('pacientes.store'), [
            'nombre' => 'Otro',
            'apellido' => 'Paciente',
            'cedula' => '0711122233',
            'telefono' => '0991112233',
            'email' => 'otro@correo.com',
            'fecha_nacimiento' => '1994-01-01',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('cedula');
    }
}
