<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class MediaRequest extends FormRequest
{
    public function rules(): array
    {
        $maxFileSizeInMegabyte = 5 * 1024;

        return [
            'file' => [
                'required',
                File::types(['png', 'jpg'])->max($maxFileSizeInMegabyte) // The uploaded file can't be more than 5Gb
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
