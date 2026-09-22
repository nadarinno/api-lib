<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'release_year' => [
                'required',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],

            'file' => [
    'nullable',
    'file',
    'mimes:pdf,doc,docx',
    'max:10240',
],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.name_required'),
            'name.max' => __('messages.name_max'),

            'description.required' => __('messages.description_required'),

            'release_year.required' => __('messages.release_year_required'),
            'release_year.integer' => __('messages.release_year_integer'),
            'release_year.min' => __('messages.release_year_invalid'),
            'release_year.max' => __('messages.release_year_invalid'),

            'file.required' => __('messages.file_required'),
            'file.file' => __('messages.file_invalid'),
            'file.mimes' => __('messages.file_type'),
            'file.max' => __('messages.file_size'),
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('messages.validation_failed'),
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}