<?php

namespace Database\Factories;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    protected $model = Paciente::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'cedula' => fake()->unique()->numerify('##########'),
            'telefono' => fake()->numerify('09########'),
            'email' => fake()->unique()->safeEmail(),
            'fecha_nacimiento' => fake()->date('Y-m-d', '-18 years'),
            'direccion' => fake()->address(),
        ];
    }
}
