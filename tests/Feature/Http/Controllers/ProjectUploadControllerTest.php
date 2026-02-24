<?php

namespace Feature\Http\Controllers;

use App\Http\Controllers\ProjectUploadController;
use App\Http\Controllers\TaskUploadController;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_a_project_file_attached_to_a_project()
    {
        Storage::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create();
        $file = UploadedFile::fake()->image('project.jpg');

        $this->post(
            action(ProjectUploadController::class, ['project' => $project]),
            [
                'file' => $file
            ]
        )->assertRedirect();

        Storage::disk()->assertExists('projects/' . $file->hashName());
        $this->assertCount(1, $project->medias);
    }
}
