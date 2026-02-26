<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Models\Project;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;

class ProjectUploadController extends Controller
{
    public function store(MediaRequest $request, Project $project)
    {
        $file = (new UploadService())->project(
            file: $request->file('file')
        );

        $project->medias()->create(
            attributes: $file->toArray()
        );

        return redirect()->back();
    }

    public function destroy(Media $media)
    {
        Storage::delete($media->path);

        $media->delete();

        return redirect()->back();
    }
}
