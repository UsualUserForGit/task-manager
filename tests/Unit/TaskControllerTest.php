<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test index route.
     */
    public function test_can_get_all_tasks()
    {
        $tasksCount = Task::count();
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount($tasksCount + 3);
    }

    /**
     * Test index with search and sort.
     */
    public function test_can_search_and_sort_tasks()
    {
        $taskData = [
            'title' => 'Задание1',
            'description' => 'Описание задания',
            'due_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'create_date' => now()->format('Y-m-d H:i:s'),
            'status' => 'выполнена',
            'priority' => 'средний',
            'category' => 'Персонал',
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task created successfully']);

        $this->assertDatabaseHas('tasks', ['title' => 'Задание1']);
    }

    /**
     * Test store route.
     */
    public function test_can_create_a_task()
    {
        $taskTitle = 'Задание' . strval(rand(1, 1000));
        $taskData = Task::factory()->create(['title' => $taskTitle])->toArray();

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task created successfully']);

        $this->assertDatabaseHas('tasks', ['title' => $taskTitle]);
    }

    /**
     * Test show route.
     */
    public function test_can_get_a_single_task()
    {
        $task = Task::factory()->create(['title' => 'Задание1']);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson(['title' => $task->title]);
    }

    /**
     * Test update route.
     */
    public function test_can_update_a_task()
    {
        $taskTitle = 'Задание' . strval(rand(1, 1000));
        $task = Task::factory()->create(['title' => $taskTitle]);

        $updatedData = [
            'title' => 'Обновленное_Задание',
            'description' => 'Обновление для задания',
            'due_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'create_date' => now()->format('Y-m-d H:i:s'),
            'status' => 'выполнена',
            'priority' => 'средний',
            'category' => 'Персонал',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task updated successfully']);

        $this->assertDatabaseHas('tasks', ['title' => 'Обновленное_Задание']);
    }

    /**
     * Test destroy route.
     */
    public function test_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task deleted successfully']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
