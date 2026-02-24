<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'project_id' => ['required', 'exists:App\Models\Project,id'],
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'status' => ['required'],
            'priority' => ['required'],
            'due_date' => ['required', 'date', Rule::date()->afterOrEqual(today())],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
