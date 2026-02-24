<?php

namespace App;

use App\Dtos\FileDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadTask
{
    public function handle(UploadedFile $file): FileDto
    {
        // Upload the file and store the record in the database
        $path = $file->store('tasks'); // tasks/{name}

        return new FileDto(
            name: basename($path),
            originalName: $file->getClientOriginalName(),
            mimeType: $file->getClientMimeType(),
            path: $path,
            disk: config('app.uploads.disk', 'local'),
            hash: hash_file(
                config('app.uploads.hash', 'sha256'),
                Storage::path($path) // respects the current disk configuration, which including faked disks
            ),
            collection: 'tasks',
            size: $file->getSize(),
        );
    }
}
