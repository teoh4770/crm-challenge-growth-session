<?php

namespace App\Services;

use App\Dtos\FileDto;
use App\UploadProject;
use App\UploadTask;
use Illuminate\Http\UploadedFile;

class UploadService
{
    public function project(UploadedFile $file): FileDto
    {
        return (new UploadProject())->handle($file);
    }

    public function task(UploadedFile $file): FileDto
    {
        return (new UploadTask())->handle($file);
    }
}
