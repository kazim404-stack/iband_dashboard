<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
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
        'product_id'   => 'required|exists:products,id',
        'image'        => 'required|array',
        'image.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'is_primary'   => 'required|in:0,1',
        'alt_text'     => 'nullable|string|max:255',
        'sort_order'   => 'nullable|integer|min:0',
    ];
}

public function messages(): array
{
    return [
        'product_id.required' => 'Product is required.',
        'product_id.exists'   => 'Selected product does not exist.',

        'image.required'      => 'At least one image is required.',
        'image.array'         => 'Images must be sent as an array.',
        'image.*.image'       => 'Each file must be an image.',
        'image.*.mimes'       => 'Each image must be a file of type: jpg, jpeg, png, webp.',
        'image.*.max'         => 'Each image may not be greater than 2MB.',


        'is_primary.required' => 'Please specify if the image is primary.',
        'is_primary.in'       => 'Primary flag must be either 0 (no) or 1 (yes).',

        'alt_text.string'     => 'Alt text must be a valid string.',
        'alt_text.max'        => 'Alt text may not exceed 255 characters.',

        'sort_order.integer'  => 'Sort order must be an integer.',
        'sort_order.min'      => 'Sort order must be at least 0.',
    ];
}

}
