<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'parent_id' => 'required',
            'name' => 'required|array',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'name.*' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:categories,slug",
            'description' => 'required|array',
            'description.*' => 'nullable|string|max:1000',
            'sort_order' => "nullable|integer|min:0",
            'status' => 'required|in:0,1',
        ];
    }
    public function messages(): array
    {
        return [
            'parent_id.exists' => 'The selected parent category does not exist.',

            'name.required'    => 'The category name is required.',
            'name.array'       => 'The category name must be an array of values.',
            'name.*.required'  => 'Each language field for the category name is required.',
            'name.*.string'    => 'Each category name must be a valid string.',
            'name.*.max'       => 'Each category name may not be greater than 255 characters.',

            'image.required' => 'The category image is required.',
            'image.image' => 'The category image must be an image file.',
            'image.mimes' => 'The category image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max'   => 'The category image may not be greater than 2 MB.',

            'slug.string'      => 'The slug must be a valid string.',
            'slug.max'         => 'The slug may not be greater than 255 characters.',
            'slug.unique'      => 'This slug is already taken.',

            'description.array'   => 'The description must be an array of values.',
            'description.*.string' => 'Each description must be a valid string.',
            'description.*.max'   => 'Each description may not be greater than 1000 characters.',

            'sort_order.required'    => 'The sort_order is required.',
            'sort_order.integer' => 'The sort order must be a valid integer.',
            'sort_order.min'     => 'The sort order must be at least 0.',

            'status.required'    => 'The status is required.',
            'status.in'          => 'The status must be either 0 (inactive) or 1 (active).',
        ];
    }
}
