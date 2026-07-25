<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cita>
 */
class CitaFactory extends Factory
{
    protected $model = Cita::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'doctor_id' => Doctor::factory(),
            'creado_por' => null,
            'fecha' => fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'hora' => fake()->randomElement(['08:00', '09:00', '10:00', '11:00', '14:00', '15:00']),
            'motivo' => fake()->sentence(4),
            'estado' => Cita::ESTADO_PENDIENTE,
            'observaciones' => null,
        ];
    }

    public function confirmada(): static
    {
        return $this->state(fn () => ['estado' => Cita::ESTADO_CONFIRMADA]);
    }

    public function atendida(): static
    {
        return $this->state(fn () => ['estado' => Cita::ESTADO_ATENDIDA]);
    }

    public function cancelada(): static
    {
        return $this->state(fn () => ['estado' => Cita::ESTADO_CANCELADA]);
    }
}
