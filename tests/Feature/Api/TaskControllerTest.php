<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\{User, Team, Project, Task};
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends RefreshDatabase
{
    protected User $user;
    protected Team $team;
    protected Project $project;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->team = Team::factory()->create(['user_id' => $this->user->id]);
        $this->project = Project::factory()->create(['team_id' => $this->team->id]);

        // Create API token
        $tokenModel = $this->user->personalAccessTokens()->create([
            'name' => 'test-token',
            'token' => 'test-token-' . uniqid(),
        ]);

        $this->token = $tokenModel->token;
    }

    /**
     * @test
     */
    public function can_list_tasks()
    {
        Task::factory(5)->create(['project_id' => $this->project->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->get('/api/v1/tasks?project_id=' . $this->project->id);

        $response->assertOk()
            ->assertJsonStructure(['success', 'data', 'pagination']);
    }

    /**
     * @test
     */
    public function can_create_task()
    {
        $data = [
            'project_id' => $this->project->id,
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'todo',
            'priority' => 'high',
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->post('/api/v1/tasks', $data);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Test Task');
    }

    /**
     * @test
     */
    public function can_update_task()
    {
        $task = Task::factory()->create(['project_id' => $this->project->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->put("/api/v1/tasks/{$task->id}", [
                'title' => 'Updated Task',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Updated Task');
    }

    /**
     * @test
     */
    public function can_delete_task()
    {
        $task = Task::factory()->create(['project_id' => $this->project->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->delete("/api/v1/tasks/{$task->id}");

        $response->assertOk();
        $this->assertModelMissing($task);
    }

    /**
     * @test
     */
    public function cannot_access_without_token()
    {
        $response = $this->get('/api/v1/tasks');
        $response->assertUnauthorized();
    }
}