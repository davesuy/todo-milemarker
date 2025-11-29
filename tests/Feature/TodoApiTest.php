<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Todo;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_a_todo(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/todos', [
                'title' => 'Test Todo',
                'description' => 'Test Description',
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Test Todo',
                'description' => 'Test Description',
            ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'user_id' => $this->user->id,
            'is_completed' => false,
        ]);
    }

    public function test_user_can_get_their_todos(): void
    {
        Todo::factory()->count(3)->create(['user_id' => $this->user->id]);
        Todo::factory()->count(2)->create(); // Other user's todos

        $response = $this->actingAs($this->user)
            ->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_update_their_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/todos/{$todo->id}", [
                'title' => 'Updated Title',
                'is_completed' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'is_completed' => true,
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_user_can_delete_their_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_user_cannot_access_others_todos(): void
    {
        $otherUserTodo = Todo::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/todos/{$otherUserTodo->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_toggle_todo_completion(): void
    {
        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/todos/{$todo->id}/toggle");

        $response->assertStatus(200)
            ->assertJson([
                'is_completed' => true,
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'is_completed' => true,
        ]);
    }

    public function test_user_can_filter_todos_by_status(): void
    {
        Todo::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => true,
        ]);
        Todo::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/todos?status=completed');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_user_can_search_todos(): void
    {
        Todo::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Buy groceries',
        ]);
        Todo::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Call dentist',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/todos?search=groceries');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.title', 'Buy groceries');
    }

    public function test_user_can_create_todo_with_category(): void
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/todos', [
                'title' => 'Test Todo',
                'category_id' => $category->id,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'category_id' => $category->id,
            ]);
    }

    public function test_guest_cannot_access_todos(): void
    {
        $response = $this->getJson('/api/todos');

        $response->assertStatus(401);
    }
}

