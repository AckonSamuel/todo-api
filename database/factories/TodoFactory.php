<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Todo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    protected $model = Todo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['completed', 'in progress', 'not started'];
        return [ 
            'title' => $this->faker->sentence, 
            'details' => $this->faker->paragraph, 
            'status' => $statuses[array_rand($statuses)],
        ];
    }
}
