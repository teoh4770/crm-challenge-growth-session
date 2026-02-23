<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
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
            'tasks' => TaskResource::collection($tasks),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
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
        return Inertia::render('Tasks/Edit', [
            'task' => new TaskResource($task),
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
