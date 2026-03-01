<?php

namespace Feature\Console;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CleanupCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleanup_command_deletes_old_soft_deleted_records()
    {
        $nonOutdatedSoftDeletedClients = Client::factory()->count(2)->create([
            'deleted_at' => now(),
        ]);
        $outdatedSoftDeletedClients = Client::factory()->count(2)->create([
            'deleted_at' => now()->subDays(30),
        ]);

        $nonOutdatedSoftDeletedProjects = Project::factory()->count(2)->create([
            'deleted_at' => now(),
        ]);
        $outdatedSoftDeletedProjects = Project::factory()->count(2)->create([
            'deleted_at' => now()->subDays(30),
        ]);

        $nonOutdatedSoftDeletedTasks = Task::factory()->count(2)->create([
            'deleted_at' => now(),
        ]);
        $outdatedSoftDeletedTasks = Task::factory()->count(2)->create([
            'deleted_at' => now()->subDays(30),
        ]);

        $this->artisan('crm:cleanup')
            ->assertSuccessful();

        $this->assertModelMissing($outdatedSoftDeletedClients);
        $this->assertModelExists($nonOutdatedSoftDeletedClients);

        $this->assertModelMissing($outdatedSoftDeletedProjects);
        $this->assertModelExists($nonOutdatedSoftDeletedProjects);

        $this->assertModelMissing($outdatedSoftDeletedTasks);
        $this->assertModelExists($nonOutdatedSoftDeletedTasks);
    }
}
