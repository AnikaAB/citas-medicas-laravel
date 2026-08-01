<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_el_paciente_puede_ver_su_propio_perfil(): void
    {
        $paciente = $this->crearPacienteConUsuario(['nombre' => 'Elena', 'apellido' => 'Jimenez']);

        $response = $this->actingAs($paciente->user)->get('/perfil');

        $response->assertStatus(200);
        $response->assertSee('Elena');
    }

    public function test_el_paciente_puede_actualizar_sus_datos(): void
    {
        $paciente = $this->crearPacienteConUsuario(['telefono' => '0987654321']);

        $response = $this->actingAs($paciente->user)->put('/perfil', [
            'nombre' => $paciente->nombre,
            'apellido' => $paciente->apellido,
            'telefono' => '0991112233',
            'direccion' => 'Nueva direccion 123',
        ]);

        $response->assertRedirect(route('perfil.edit'));
        $this->assertDatabaseHas('pacientes', [
            'id' => $paciente->id,
            'telefono' => '0991112233',
            'direccion' => 'Nueva direccion 123',
        ]);
    }

    public function test_actualizar_el_perfil_sincroniza_el_nombre_mostrado_en_la_cuenta(): void
    {
        $paciente = $this->crearPacienteConUsuario(['nombre' => 'Juan', 'apellido' => 'Perez']);

        $this->actingAs($paciente->user)->put('/perfil', [
            'nombre' => 'Juan Carlos',
            'apellido' => 'Perez Gomez',
            'telefono' => $paciente->telefono,
            'direccion' => $paciente->direccion,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $paciente->user_id,
            'name' => 'Juan Carlos Perez Gomez',
        ]);
    }
}
