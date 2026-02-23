<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
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
}
