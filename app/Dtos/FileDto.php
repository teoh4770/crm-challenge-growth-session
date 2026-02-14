<?php

namespace App\Dtos;

// a data object used by UploadService
readonly class FileDto
{
    public function __construct(
        public string      $name,
        public string      $originalName,
        public string      $mimeType,
        public string      $path,
        public string      $disk,
        public string      $hash,
        public null|string $collection = null,
        public int         $size,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'file_name' => $this->originalName,
            'mime_type' => $this->mimeType,
            'path' => $this->path,
            'disk' => $this->disk,
            'file_hash' => $this->hash,
            'collection' => $this->collection,
            'size' => $this->size,
        ];
    }
}
