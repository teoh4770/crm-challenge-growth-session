<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = null;

        if (auth()->user()->can('manage tasks')) {
            $tasks = Task::with(['project', 'user'])->latest()->get();
        } else if (auth()->user()->can('view own tasks')) {
            $tasks = Task::with(['project', 'user'])->where('user_id', auth()->id())->latest()->get();
        }

        return Inertia::render('Tasks/Index', [
            'can' => [
                'delete_task' => auth()->user()->can('manage tasks')
            ],
            'tasks' => TaskResource::collection($tasks)
        ]);
    }

    public function create()
    {
        $taskStatuses = collect(TaskStatusEnum::cases())->map(function ($enum) {
            return [
                'label' => $enum->name,
                'value' => $enum->value,
            ];
        });

        $taskPriorities = collect(TaskPriorityEnum::cases())->map(function ($enum) {
            return [
                'label' => $enum->name,
                'value' => $enum->value,
            ];
        });

        return Inertia::render('Tasks/Create', [
            'users' => UserResource::collection(User::query()->get()),
            'projects' => ProjectResource::collection(Project::query()->get()),
            'taskStatuses' => $taskStatuses,
            'taskPriorities' => $taskPriorities
        ]);
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => new TaskResource($task),
        ]);
    }

    public function edit(Task $task)
    {
        $taskStatuses = collect(TaskStatusEnum::cases())->map(function ($enum) {
            return [
                'label' => $enum->name,
                'value' => $enum->value,
            ];
        });

        $taskPriorities = collect(TaskPriorityEnum::cases())->map(function ($enum) {
            return [
                'label' => $enum->name,
                'value' => $enum->value,
            ];
        });

        return Inertia::render('Tasks/Edit', [
            'task' => new TaskResource($task),
            'users' => UserResource::collection(User::query()->get()),
            'projects' => ProjectResource::collection(Project::query()->get()),
            'taskStatuses' => $taskStatuses,
            'taskPriorities' => $taskPriorities,
            'taskMedias' => MediaResource::collection($task->medias),
        ]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (auth()->user()->cannot('manage tasks')) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
