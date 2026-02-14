<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Services\UploadService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProjectMediaUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MediaRequest $request)
    {
        // Get the file uploaded and the hash name to be stored
        $uploadService = new UploadService();
        $file = $uploadService->project(
            file: $request->file('file')
        );

        Media::query()->create(
            attributes: $file->toArray()
        );

        return redirect()->back();
    }
}
