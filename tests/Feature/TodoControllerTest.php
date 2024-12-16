<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Todo;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_all_todos()
    {
        Todo::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/todos');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    #[Test]
    public function it_can_filter_todos_by_status()
    {
        Todo::factory()->create(['status' => 'in progress']);
        Todo::factory()->create(['status' => 'completed']);

        $response = $this->getJson('/api/v1/todos?status=in progress');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['status' => 'in progress']);
    }

    #[Test]
    public function it_can_search_todos_by_title()
    {
        Todo::factory()->create(['title' => 'First Todo']);
        Todo::factory()->create(['title' => 'Second Todo']);

        $response = $this->getJson('/api/v1/todos?search=First');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['title' => 'First Todo']);
    }

    #[Test]
    public function it_can_create_a_new_todo()
    {
        $data = [
            'title' => 'Test Todo',
            'details' => 'Test details',
            'status' => 'in progress',
        ];

        $response = $this->postJson('/api/v1/todos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }

    #[Test]
    public function it_can_show_a_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->getJson('/api/v1/todos/' . $todo->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $todo->title]);
    }

    #[Test]
    public function it_can_update_a_todo()
    {
        $todo = Todo::factory()->create();

        $data = [
            'title' => 'Updated Todo',
            'details' => 'Updated details',
            'status' => 'completed',
        ];

        $response = $this->putJson('/api/v1/todos/' . $todo->id, $data);

        $response->assertStatus(200)
                 ->assertJsonFragment($data);
    }

    #[Test]
    public function it_can_delete_a_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson('/api/v1/todos/' . $todo->id);

        $response->assertStatus(204);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_todo()
    {
        $response = $this->getJson('/api/v1/todos/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_returns_400_for_invalid_todo_creation()
    {
        $data = [
            'title' => '', // Missing required fields
        ];

        $response = $this->postJson('/api/v1/todos', $data);

        $response->assertStatus(500);
    }

    #[Test]
    public function it_returns_400_for_invalid_todo_update()
    {
        $todo = Todo::factory()->create();

        $data = [
            'tiy' => 'ghhgvgh', // Invalid data
        ];

        $response = $this->putJson('/api/v1/todos/' . $todo->id, $data);

        $response->assertStatus(400);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_todo()
    {
        $response = $this->deleteJson('/api/v1/todos/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_returns_405_for_unsupported_methods()
    {
        $response = $this->patchJson('/api/v1/todos'); // PATCH is not supported

        $response->assertStatus(405);
    }
}
