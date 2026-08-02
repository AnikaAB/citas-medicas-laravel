<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class RecepcionistaTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_el_admin_puede_ver_el_listado_de_recepcionistas(): void
    {
        $admin = $this->crearAdmin();
        $this->crearRecepcionista(['name' => 'Maria Fernanda Lopez']);
        $this->crearDoctorConUsuario();

        $response = $this->actingAs($admin)->get(route('recepcionistas.index'));

        $response->assertStatus(200);
        $response->assertSee('Maria Fernanda Lopez');
    }

    public function test_el_listado_solo_muestra_recepcionistas_no_otros_roles(): void
    {
        $admin = $this->crearAdmin();
        $this->crearRecepcionista(['name' => 'Recepcionista Uno']);
        $doctor = $this->crearDoctorConUsuario();

        $response = $this->actingAs($admin)->get(route('recepcionistas.index'));

        $response->assertDontSee($doctor->email);
    }

    public function test_el_admin_puede_registrar_una_nueva_recepcionista(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post(route('recepcionistas.store'), [
            'name' => 'Nueva Recepcionista',
            'email' => 'nueva.recepcionista@clinica.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('recepcionistas.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'nueva.recepcionista@clinica.com',
            'rol' => 'recepcionista',
            'activo' => true,
        ]);
    }

    public function test_no_permite_registrar_recepcionista_sin_datos_obligatorios(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post(route('recepcionistas.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_no_permite_registrar_recepcionista_con_correo_duplicado(): void
    {
        $admin = $this->crearAdmin();
        $this->crearRecepcionista(['email' => 'repetida@clinica.com']);

        $response = $this->actingAs($admin)->post(route('recepcionistas.store'), [
            'name' => 'Otra Recepcionista',
            'email' => 'repetida@clinica.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_el_formulario_de_alta_ignora_cualquier_rol_enviado_manualmente(): void
    {
        // Aunque alguien manipule el form e inyecte 'rol' => 'admin' en el POST,
        // el controlador debe forzar SIEMPRE rol=recepcionista.
        $admin = $this->crearAdmin();

        $this->actingAs($admin)->post(route('recepcionistas.store'), [
            'name' => 'Intento Escalada',
            'email' => 'intento@clinica.com',
            'password' => 'password123',
            'rol' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'intento@clinica.com',
            'rol' => 'recepcionista',
        ]);
    }

    public function test_el_admin_puede_editar_una_recepcionista_existente(): void
    {
        $admin = $this->crearAdmin();
        $recepcionista = $this->crearRecepcionista(['name' => 'Nombre Viejo']);

        $response = $this->actingAs($admin)->put(route('recepcionistas.update', $recepcionista), [
            'name' => 'Nombre Nuevo',
            'email' => $recepcionista->email,
        ]);

        $response->assertRedirect(route('recepcionistas.index'));
        $this->assertDatabaseHas('users', [
            'id' => $recepcionista->id,
            'name' => 'Nombre Nuevo',
        ]);
    }

    public function test_no_permite_editar_como_recepcionista_a_un_usuario_que_no_lo_es(): void
    {
        $admin = $this->crearAdmin();
        $doctor = $this->crearDoctorConUsuario();

        $response = $this->actingAs($admin)->get(route('recepcionistas.edit', $doctor->user_id));

        $response->assertNotFound();
    }

    public function test_eliminar_desactiva_en_vez_de_borrar_para_conservar_el_historial(): void
    {
        $admin = $this->crearAdmin();
        $recepcionista = $this->crearRecepcionista(['activo' => true]);

        $response = $this->actingAs($admin)->delete(route('recepcionistas.destroy', $recepcionista));

        $response->assertRedirect(route('recepcionistas.index'));
        $this->assertDatabaseHas('users', [
            'id' => $recepcionista->id,
            'activo' => false,
        ]);
    }

    public function test_el_admin_puede_reactivar_una_recepcionista_desactivada(): void
    {
        $admin = $this->crearAdmin();
        $recepcionista = $this->crearRecepcionista(['activo' => false]);

        $response = $this->actingAs($admin)->patch(route('recepcionistas.activar', $recepcionista));

        $response->assertRedirect(route('recepcionistas.index'));
        $this->assertDatabaseHas('users', [
            'id' => $recepcionista->id,
            'activo' => true,
        ]);
    }

    public function test_un_recepcionista_no_puede_acceder_a_su_propio_modulo_de_gestion(): void
    {
        $recepcionista = $this->crearRecepcionista();

        $response = $this->actingAs($recepcionista)->get(route('recepcionistas.create'));

        $response->assertForbidden();
    }

    public function test_un_doctor_no_puede_acceder_al_modulo_de_recepcionistas(): void
    {
        $doctor = $this->crearDoctorConUsuario();

        $response = $this->actingAs($doctor->user)->get(route('recepcionistas.index'));

        $response->assertForbidden();
    }
}
