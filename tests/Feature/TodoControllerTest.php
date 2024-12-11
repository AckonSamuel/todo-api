<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Todo;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_todos()
    {
        Todo::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/todos');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_filter_todos_by_status()
    {
        Todo::factory()->create(['status' => 'pending']);
        Todo::factory()->create(['status' => 'completed']);

        $response = $this->getJson('/api/v1/todos?status=pending');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['status' => 'pending']);
    }

    /** @test */
    public function it_can_search_todos_by_title()
    {
        Todo::factory()->create(['title' => 'First Todo']);
        Todo::factory()->create(['title' => 'Second Todo']);

        $response = $this->getJson('/api/v1/todos?search=First');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['title' => 'First Todo']);
    }

    /** @test */
    public function it_can_create_a_new_todo()
    {
        $data = [
            'title' => 'Test Todo',
            'details' => 'Test details',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/v1/todos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }

    /** @test */
    public function it_can_show_a_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->getJson('/api/v1/todos/' . $todo->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $todo->title]);
    }

    /** @test */
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

    /** @test */
    public function it_can_delete_a_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson('/api/v1/todos/' . $todo->id);

        $response->assertStatus(204);
    }

}
