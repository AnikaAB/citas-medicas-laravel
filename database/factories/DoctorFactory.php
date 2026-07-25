<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'especialidad' => fake()->randomElement([
                'Medicina General', 'Pediatria', 'Cardiologia', 'Ginecologia', 'Dermatologia',
            ]),
            'telefono' => fake()->numerify('09########'),
            'email' => fake()->unique()->safeEmail(),
            'horario_inicio' => '08:00:00',
            'horario_fin' => '17:00:00',
        ];
    }
}
