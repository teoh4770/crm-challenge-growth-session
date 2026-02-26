<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Models\Project;
use App\Models\Task;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;

class TaskUploadDeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Task $task, Media $media)
    {
        Storage::delete($media->path);

        $task->medias()->where('file_hash', $media->file_hash)->delete();

        return redirect()->back();
    }
}
