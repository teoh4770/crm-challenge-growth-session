<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Console\Command;

class CleanupCommand extends Command
{
    protected $signature = 'crm:cleanup';

    protected $description = 'Purge soft-deleted records (clients, projects, tasks) older than or equal to 30 days';

    public function handle(): void
    {
        Client::where('deleted_at', '<=', now()->subDays(30))->forceDelete();
        Project::where('deleted_at', '<=', now()->subDays(30))->forceDelete();
        Task::where('deleted_at', '<=', now()->subDays(30))->forceDelete();
    }
}
