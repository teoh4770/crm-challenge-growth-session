<?php

namespace Feature\Http\Controllers;

use App\Http\Controllers\TaskUploadController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_a_task_file_thats_attached_to_a_task()
    {
        Storage::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create();
        $file = UploadedFile::fake()->image('task.jpg');

        $this->post(
            action(TaskUploadController::class, ['task' => $task]),
            [
                'file' => $file
            ]
        )->assertRedirect();

        Storage::disk()->assertExists('tasks/' . $file->hashName());
        $this->assertCount(1, $task->medias);
    }
}
