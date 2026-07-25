<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas FUNCIONALES del modulo de autenticacion (login).
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_login_carga_correctamente(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_un_usuario_puede_iniciar_sesion_con_credenciales_correctas(): void
    {
        $usuario = User::factory()->create([
            'email' => 'usuario@correo.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.attempt'), [
            'email' => 'usuario@correo.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_no_permite_iniciar_sesion_con_password_incorrecto(): void
    {
        User::factory()->create([
            'email' => 'usuario@correo.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login.attempt'), [
            'email' => 'usuario@correo.com',
            'password' => 'incorrecto',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_usuario_autenticado_puede_cerrar_sesion(): void
    {
        $usuario = User::factory()->create();

        $response = $this->actingAs($usuario)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_un_usuario_autenticado_no_puede_ver_el_formulario_de_login(): void
    {
        $usuario = User::factory()->create();

        $response = $this->actingAs($usuario)->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }
}
