<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $admin = \App\Models\User::factory()->create([
            'department_id' => null,
        ]);

        return [
            'name' => fake()->unique()->company(),
            'admin_id' => $admin->id,
        ];
    }
}
