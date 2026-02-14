<?php

namespace App\Services;

use App\Dtos\FileDto;
use App\UploadProject;
use Illuminate\Http\UploadedFile;

class UploadService
{
    public function project(UploadedFile $file): FileDto
    {
        return (new UploadProject())->handle($file);
    }
}
