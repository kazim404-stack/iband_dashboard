<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
        return [
            'name'              => 'required|array',
            'name.*'            => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'name.required'     => 'The title field is required.',
            'name.array'        => 'The title must be an array.',
            'name.*.required'   => 'Each title is required.',
            'name.*.string'     => 'Each title must be a string.',
            'name.*.max'        => 'Each title may not be greater than 255 characters.',
        ];
    }
}
