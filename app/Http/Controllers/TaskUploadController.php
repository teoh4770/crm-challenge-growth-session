<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\UploadService;

class TaskUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MediaRequest $request, Task $task)
    {
        $uploadService = new UploadService();

        $file = $uploadService->task(
            file: $request->file('file')
        );

        $task->medias()->create(
            attributes: $file->toArray()
        );

        return redirect()->back();
    }
}
