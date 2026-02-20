<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Media */
class MediaResource extends JsonResource
{
    const KILOBYTE_TO_BYTE = 1024;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fileName' => $this->file_name,
            'size' => round($this->size / self::KILOBYTE_TO_BYTE, 2)
        ];
    }
}
