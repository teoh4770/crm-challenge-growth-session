<?php

namespace Tests\Unit;

use App\Models\Media;
use App\Models\Project;
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
            ->has(Media::factory()->count(3), 'medias')
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
}
