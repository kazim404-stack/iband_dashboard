<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
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
            'title'              => 'required|array',
            'title.*'            => 'required|string|max:255',
            'description'              => 'required|array',
            'description.*'            => 'required|string|max:500',
            'status'              => 'required|numeric',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'     => 'The title field is required.',
            'title.array'        => 'The title must be an array.',
            'title.*.required'   => 'Each title is required.',
            'title.*.string'     => 'Each title must be a string.',
            'title.*.max'        => 'Each title may not be greater than 255 characters.',

            'description.required'   => 'The description field is required.',
            'description.array'      => 'The description must be an array.',
            'description.*.required' => 'Each description is required.',
            'description.*.string'   => 'Each description must be a string.',
            'description.*.max'      => 'Each description may not be greater than 500 characters.',

            'status.required'    => 'The status field is required.',
            'status.numeric'     => 'The status must be a number.',
        ];
    }
}
