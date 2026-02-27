<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Models\Task;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;

class TaskUploadController extends Controller
{
    public function store(MediaRequest $request, Task $task)
    {
        $file = (new UploadService())->task(
            file: $request->file('file')
        );

        $task->medias()->create(
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

    public function download(Media $media)
    {
        return Storage::download($media->path);
    }
}
