<?php

namespace Feature\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setting up roles and permissions
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $manageUsersPermission = Permission::create(['name' => 'manage users']);
        $manageClientsPermission = Permission::create(['name' => 'manage clients']);
        $manageProjectsPermission = Permission::create(['name' => 'manage projects']);
        $manageTasksPermission = Permission::create(['name' => 'manage tasks']);
        $viewOwnProjectsPermission = Permission::create(['name' => 'view own projects']);
        $viewOwnTasksPermission = Permission::create(['name' => 'view own tasks']);

        $adminRole->syncPermissions([
            $manageUsersPermission,
            $manageClientsPermission,
            $manageProjectsPermission,
            $manageTasksPermission,
            $viewOwnProjectsPermission,
            $viewOwnTasksPermission,
        ]);

        $userRole->syncPermissions([
            $viewOwnProjectsPermission,
            $viewOwnTasksPermission,
        ]);

        // Setting up users
        $this->user = User::factory()->user()->create();
        $this->admin = User::factory()->admin()->create();
    }

    // INDEX
    public function test_list_tasks_requires_authentication()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_all_tasks()
    {
        $tasks = Task::factory()->count(10)->create();

        $response = $this->actingAs($this->admin)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn(Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks.data', $tasks->count())
        );
    }

    public function test_user_can_only_view_own_tasks()
    {
        $userTasks = Task::factory()->count(10)->create([
            'user_id' => $this->user->id,
        ]);
        Task::factory()->count(10)->create();

        $response = $this->actingAs($this->user)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn(Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks.data', $userTasks->count())
        );
    }

    // CREATE
    public function test_show_create_task_page_requires_authentication()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_show_create_task_page()
    {
        $response = $this->actingAs($this->admin)->get(route('tasks.create'));

        $response->assertStatus(200)
            ->assertInertia(fn(Assert $page) => $page
                ->component('Tasks/Create'));
    }

    public function test_user_can_show_create_task_page()
    {
        $response = $this->actingAs($this->user)->get(route('tasks.create'));

        $response->assertStatus(200)
            ->assertInertia(fn(Assert $page) => $page
                ->component('Tasks/Create'));
    }

    // STORE
    public function test_create_task_requires_authentication()
    {
        $response = $this->post(route('tasks.store'));

        $response->assertRedirect(route('login'));
    }

    public function test_can_create_task_with_valid_data()
    {
        $project = Project::factory()->create();

        $task = Task::factory()->make([
            'project_id' => $project->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->admin)->post(route('tasks.store'), $task->toArray());

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseHas('tasks', $task->except(['created_at', 'updated_at', 'due_date']));
    }

    public function test_cannot_create_task_without_required_fields()
    {
        $response = $this->actingAs($this->user)->post(route('tasks.store'), []);

        $response->assertSessionHasErrors(['title', 'description', 'project_id', 'user_id', 'status', 'priority', 'due_date']);
        $this->assertDatabaseEmpty('tasks');
    }

    // SHOW
    public function test_show_task_requires_authentication()
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.show', $task));

        $response->assertRedirect(route('login'));
    }

    public function test_can_show_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('tasks.show', $task));

        $response->assertStatus(200)
            ->assertInertia(fn(Assert $page) => $page
                ->component('Tasks/Show')
                ->has('task.data', fn(Assert $page) => $page->where('id', $task->id)->etc())
            );
    }

    // EDIT
    public function test_show_edit_task_page_requires_authentication()
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_task_edit_page()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('tasks.edit', $task));

        $response->assertStatus(200);
        $response->assertInertia(fn(Assert $page) => $page
            ->component('Tasks/Edit')
            ->has('task.data', fn(Assert $page) => $page->where('id', $task->id)->etc())
        );
    }

    // UPDATE
    public function test_update_task_requires_authentication()
    {
        $task = Task::factory()->create();

        $response = $this->put(route('tasks.update', $task));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('tasks.update', $task), [
            ...$task->toArray(),
            'title' => 'updated title',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $task->refresh();
        $this->assertEquals('updated title', $task->title);
    }

    // DESTROY
    public function test_delete_task_requires_authentication()
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_delete_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task));

        $response->assertForbidden();
    }

    public function test_admin_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
