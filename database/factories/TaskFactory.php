<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    /**
     * Название модели, соответствующей фабрике.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+1 week')->format('Y-m-d H:i:s'),
            'create_date' => now()->format('Y-m-d H:i:s'),
            'status' => $this->faker->randomElement(['не выполнена', 'выполнена']),
            'priority' => $this->faker->randomElement(['низкий', 'средний', 'высокий']),
            'category' => $this->faker->randomElement(['Работа', 'Персонал', 'Дом']),
        ];
    }
}
