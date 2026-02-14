<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Project;
use App\Services\UploadService;

class UploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MediaRequest $request, Project $project)
    {
        $uploadService = new UploadService();

        // If project is the second argument, use project method from upload service
        $file = $uploadService->project(
            file: $request->file('file')
        );

        // Create media related to the project
        $project->medias()->create(
            attributes: $file->toArray()
        );

        return redirect()->back();
    }
}
