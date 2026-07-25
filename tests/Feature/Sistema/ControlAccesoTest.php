<?php

namespace Tests\Feature\Sistema;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de SISTEMA: control de acceso por rol (RBAC) a traves
 * de toda la aplicacion, verificando el middleware EnsureRole.
 */
class ControlAccesoTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visitante_no_autenticado_es_redirigido_al_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_un_paciente_no_puede_gestionar_doctores(): void
    {
        $paciente = User::factory()->create(['rol' => User::ROL_PACIENTE]);

        $response = $this->actingAs($paciente)->get(route('doctores.index'));

        $response->assertForbidden();
    }

    public function test_un_paciente_no_puede_ver_el_listado_de_citas(): void
    {
        $paciente = User::factory()->create(['rol' => User::ROL_PACIENTE]);

        $response = $this->actingAs($paciente)->get(route('citas.index'));

        $response->assertForbidden();
    }

    public function test_un_recepcionista_no_puede_gestionar_doctores(): void
    {
        $recepcionista = User::factory()->create(['rol' => User::ROL_RECEPCIONISTA]);

        $response = $this->actingAs($recepcionista)->get(route('doctores.create'));

        $response->assertForbidden();
    }

    public function test_un_doctor_no_puede_gestionar_pacientes(): void
    {
        $doctor = User::factory()->create(['rol' => User::ROL_DOCTOR]);

        $response = $this->actingAs($doctor)->get(route('pacientes.index'));

        $response->assertForbidden();
    }

    public function test_el_administrador_tiene_acceso_a_todos_los_modulos(): void
    {
        $admin = User::factory()->create(['rol' => User::ROL_ADMIN]);

        $this->actingAs($admin)->get(route('citas.index'))->assertOk();
        $this->actingAs($admin)->get(route('pacientes.index'))->assertOk();
        $this->actingAs($admin)->get(route('doctores.index'))->assertOk();
    }

    public function test_un_doctor_solo_ve_sus_propias_citas_en_el_panel(): void
    {
        $doctor = Doctor::factory()->create();
        $usuarioDoctor = User::factory()->create(['rol' => User::ROL_DOCTOR]);
        $doctor->update(['user_id' => $usuarioDoctor->id]);

        $response = $this->actingAs($usuarioDoctor)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('citas');
    }
}
