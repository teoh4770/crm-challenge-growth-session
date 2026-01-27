<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return Inertia::render('Projects/Index', [
            'projects' => ProjectResource::collection($projects)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return Project::create($data);
    }

    public function show(Project $project)
    {
        return $project;
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([

        ]);

        $project->update($data);

        return $project;
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json();
    }
}
