<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas UNITARIAS del modelo User: helpers de rol.
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_es_admin_devuelve_verdadero_solo_para_el_rol_admin(): void
    {
        $admin = User::factory()->admin()->make();
        $paciente = User::factory()->paciente()->make();

        $this->assertTrue($admin->esAdmin());
        $this->assertFalse($paciente->esAdmin());
    }

    public function test_es_doctor_devuelve_verdadero_solo_para_el_rol_doctor(): void
    {
        $doctorUser = User::factory()->doctorUser()->make();
        $recepcionista = User::factory()->recepcionista()->make();

        $this->assertTrue($doctorUser->esDoctor());
        $this->assertFalse($recepcionista->esDoctor());
    }

    public function test_la_contrasena_no_se_expone_al_serializar(): void
    {
        $usuario = User::factory()->create();

        $this->assertArrayNotHasKey('password', $usuario->toArray());
    }
}
