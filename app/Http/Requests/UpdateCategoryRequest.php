<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $category = $this->route('category');

        return [
            'parent_id'     => 'required',
            'name'          => 'required|array',
            'name.*'        => 'required|string|max:255',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($category->id),
            ],

            'description'   => 'nullable|array',
            'description.*' => 'nullable|string|max:1000',

            'sort_order'    => 'nullable|integer|min:0',
            'status'        => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.required'   => 'The parent id is required.',


            'name.required'      => 'The category name is required.',
            'name.array'         => 'The category name must be an array.',
            'name.*.required'    => 'Each language field for the category name is required.',
            'name.*.string'      => 'Each category name must be a valid string.',
            'name.*.max'         => 'Each category name may not exceed 255 characters.',

            'image.required' => 'The category image is required.',
            'image.image' => 'The category image must be an image file.',
            'image.mimes' => 'The category image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max'   => 'The category image may not be greater than 2 MB.',

            'slug.string'        => 'The slug must be a valid string.',
            'slug.max'           => 'The slug may not exceed 255 characters.',
            'slug.unique'        => 'This slug is already in use.',

            'description.array'  => 'The description must be an array.',
            'description.*.string' => 'Each description must be a valid string.',
            'description.*.max'  => 'Each description may not exceed 1000 characters.',

             'sort_order.required'      => 'The sort order  is required.',
            'sort_order.integer' => 'The sort order must be a number.',
            'sort_order.min'     => 'The sort order must be at least 0.',

            'status.required'    => 'The status is required.',
            'status.in'          => 'The status must be either 0 (inactive) or 1 (active).',
        ];
    }
}
