<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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

    public function rules()
    {
        $product = $this->route('product');
        return [
            'name' => 'required|array', // JSON field
            'name.*' => 'required|string|max:255', // Each language/name in JSON
            'category_id' => 'required|exists:categories,id',
            'sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'short_description' => 'nullable|array',
            'short_description.*' => 'nullable|string|max:1000',
            'long_description' => 'nullable|array',
            'long_description.*' => 'nullable|string|max:5000',
            'type' => 'required|in:simple,variable,service',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'name.array' => 'The product name must be provided in multiple languages.',
            'name.*.required' => 'Each product name translation is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'sku.unique' => 'This SKU is already taken.',
            'type.required' => 'Please select a product type.',
            'type.in' => 'The product type must be simple, variable, or service.',
            'weight.numeric' => 'Weight must be a number.',
            'length.numeric' => 'Length must be a number.',
            'width.numeric' => 'Width must be a number.',
            'height.numeric' => 'Height must be a number.',
            'status.in' => 'Status must be either 0 (inactive) or 1 (active).',
        ];
    }
}
