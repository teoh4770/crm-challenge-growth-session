<?php

namespace Tests\Unit;

use App\Models\Media;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use LazilyRefreshDatabase;

    /*
        test('can create project with valid data')
        test('project belongs to client')
        test('project belongs to user')
        test('project has many tasks')
        test('project can have media attachments')
        test('date accessors return formatted dates')
        test('in_progress scope filters correctly')
    */

    public function test_project_has_many_medias()
    {
        $project = Project::factory()
            ->hasMedias(3)
            ->create();

        $this->assertCount(3, $project->medias);
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
}
