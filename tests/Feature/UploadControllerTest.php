<?php

namespace Feature;

use App\Http\Controllers\UploadController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_upload_a_project_file()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('project.jpg');

        $this->post(
            action(UploadController::class),
            [
                'file' => $file,
            ]
        )->assertRedirect();

        Storage::disk()->assertExists('projects/' . $file->hashName());
    }

    public function test_can_upload_a_project_file_attached_to_a_project()
    {
        Storage::fake();

        $project = Project::factory()->create();
        $file = UploadedFile::fake()->image('project.jpg');

        $this->post(
            action(UploadController::class, ['project' => $project]),
            [
                'file' => $file
            ]
        )->assertRedirect();

        Storage::disk()->assertExists('projects/' . $file->hashName());
        // assert that in media table exists a media attached to the project
        $this->assertCount(1, $project->medias);
    }
}
