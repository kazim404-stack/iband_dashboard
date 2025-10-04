<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeValueRequest extends FormRequest
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
            'attribute_id' => 'required|exists:attributes,id',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('attribute_values', 'slug')->ignore($this->route('attribute_value')),
            ],
            'value' => 'required|array',
            'value.*' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'The attribute field is required.',
            'attribute_id.exists' => 'The selected attribute does not exist.',

            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a valid string.',
            'slug.max' => 'The slug may not exceed 255 characters.',
            'slug.unique' => 'This slug has already been taken.',

            'value.required' => 'The value field is required.',
            'value.array' => 'The value must be an array of translations.',
            'value.*.required' => 'Each translated value is required.',
            'value.*.string' => 'Each translated value must be a valid string.',
            'value.*.max' => 'Each translated value may not exceed 255 characters.',

            'sort_order.integer' => 'The sort order must be an integer.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }
}
