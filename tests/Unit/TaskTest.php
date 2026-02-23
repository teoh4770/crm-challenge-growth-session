<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use LazilyRefreshDatabase;

    /*
        test('can create task with valid data')
        test('task belongs to project')
        test('task belongs to user')
        test('task can have media attachments')
        test('due_date accessor returns formatted date')
        test('pending scope excludes completed tasks')
     */

    public function test_task_belongs_to_project()
    {
        $project = Project::factory()
            ->hasTasks(3)
            ->create();

        $tasks = Task::whereBelongsTo($project)->get();

        $this->assertEquals($project->id, $tasks->first()->project->id);
    }

    public function test_task_belongs_to_user()
    {
        $user = User::factory()
            ->hasTasks(3)
            ->create();

        $tasks = Task::whereBelongsTo($user)->get();

        $this->assertEquals($user->id, $tasks->first()->user->id);
    }

    public function test_task_can_have_media_attachments()
    {
        $task = Task::factory()->hasMedias(3)->create();

        $this->assertEquals(3, $task->medias->count());
    }

    public function test_task_can_be_soft_deleted()
    {
        $task = Task::factory()->create();

        $task->delete();

        $this->assertSoftDeleted($task);
    }

    public function test_due_date_accessor_return_formatted_date()
    {
        $task = Task::factory()->create([
            'due_date' => '2026-02-22',
        ]);

        $this->assertEquals(Carbon::parse('2026-02-22')->format('m/d/Y'), $task->due_date);
    }

    public function test_todo_scope_returns_todo_tasks()
    {
        $todoTasks = Task::factory()->count(2)->create([
           'status' => TaskStatusEnum::Todo
        ]);

        $completedTasks = Task::factory()->count(2)->create([
            'status' => TaskStatusEnum::Completed
        ]);

        $this->assertEquals($todoTasks->pluck('id'), Task::todo()->get()->pluck('id'));
    }

    public function test_completed_scope_returns_completed_tasks()
    {
        $todoTasks = Task::factory()->count(2)->create([
            'status' => TaskStatusEnum::Todo
        ]);

        $completedTasks = Task::factory()->count(2)->create([
            'status' => TaskStatusEnum::Completed
        ]);

        $this->assertEquals($completedTasks->pluck('id'), Task::completed()->get()->pluck('id'));
    }
}
