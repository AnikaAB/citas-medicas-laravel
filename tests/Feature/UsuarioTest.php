<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_el_admin_puede_ver_el_listado_de_usuarios(): void
    {
        $admin = $this->crearAdmin();
        $recepcionista = $this->crearRecepcionista(['name' => 'Maria Fernanda Lopez']);

        $response = $this->actingAs($admin)->get('/usuarios');

        $response->assertStatus(200);
        $response->assertSee('Maria Fernanda Lopez');
    }

    public function test_el_admin_puede_activar_y_desactivar_a_otro_usuario(): void
    {
        $admin = $this->crearAdmin();
        $recepcionista = $this->crearRecepcionista(['activo' => true]);

        $response = $this->actingAs($admin)->patch("/usuarios/{$recepcionista->id}/estado");

        $response->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseHas('users', [
            'id' => $recepcionista->id,
            'activo' => false,
        ]);
    }

    public function test_el_admin_no_puede_desactivar_su_propia_cuenta(): void
    {
        $admin = $this->crearAdmin(['activo' => true]);

        $response = $this->actingAs($admin)->patch("/usuarios/{$admin->id}/estado");

        $response->assertSessionHasErrors('usuario');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'activo' => true,
        ]);
    }
}
