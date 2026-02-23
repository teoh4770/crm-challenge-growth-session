<?php

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->foreignIdFor(Project::class)->constrained('projects')->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained('users')->cascadeOnDelete();
            $table->string('status')->default(TaskStatusEnum::Todo);
            $table->string('priority')->default(TaskPriorityEnum::Low);
            $table->date('due_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
