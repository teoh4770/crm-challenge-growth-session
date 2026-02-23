<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_project_has_many_medias()
    {
        $project = Project::factory()
            ->hasMedias(3)
            ->create();

        $this->assertCount(3, $project->medias);
    }

    public function test_project_has_many_tasks()
    {
        $project = Project::factory()
            ->has(Task::factory()->count(3), 'tasks')
            ->create();

        $this->assertCount(3, $project->tasks);
    }

    public function test_soft_deleted_projects_are_not_in_default_queries()
    {
        $projects = Project::factory()->count(4)->create();

        Project::query()->first()->delete();

        $this->assertDatabaseCount('projects', 4);
        $this->assertCount(3, Project::query()->get());
    }

    public function test_data_accessors_return_formatted_dates()
    {
        $deadlineInput = '2026-02-22';
        Project::factory()->create([
            'deadline' => $deadlineInput
        ]);

        $project = Project::query()->first();

        $this->assertEquals(Carbon::parse($deadlineInput)->format('m/d/Y'), $project->deadline);
    }

    public function test_in_progress_scope_filters_correctly()
    {
        Project::factory()->count(2)->create([
            'status' => ProjectStatusEnum::Completed
        ]);
        Project::factory()->count(3)->create([
            'status' => ProjectStatusEnum::InProgress
        ]);

        $this->assertCount(3, Project::inProgress()->get());
    }
}
