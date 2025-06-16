<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $task = $this->route('task');
        $taskId = is_object($task) ? $task->getKey() : $task;

        return [
            // 'taskkey' => 'string',
            'title' => 'required|string|max:255|unique:tasks,title,' . $taskId,
            'description' => 'required|string|max:255|min:10',
            'status' => 'nullable|in:pending,in_progress,completed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // 'taskkey.string' => 'Task key must be a string.',
            'title.required' => 'Title is required.',
            'title.unique' => 'Title must be unique and task is already exists.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
            'description.max' => 'Description may not be greater than 255 characters.',
            'description.min' => 'Description must be at least 10 characters.',
            'status.in' => 'Status must be one of the following: pending, in_progress, completed.',
            'status.nullable' => 'Status is optional and can be null.',
            
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
