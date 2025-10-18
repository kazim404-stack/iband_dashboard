<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'currency_id' => 'required|exists:currencies,id',
            'attribute_value_ids' => 'required|array',
            'attribute_value_ids.*' => 'exists:attribute_values,id',
            'sku' => [
                'nullable',
                'string',
                'max:100',

                Rule::unique('product_variants', 'sku')->ignore($this->route('product_variant')),
            ],
            'barcode' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            // 'qty' => 'required|integer|min:0',
            'min_order_qty' => 'nullable|integer|min:1',
            'max_order_qty' => 'nullable|integer|gte:min_order_qty',
            'is_track_stock' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID is required.',
            'product_id.exists' => 'Selected product does not exist.',


            'currency_id.required' => 'The currency field is required.',
            'currency_id.exists' => 'The selected currency does not exist.',

            'attribute_value_ids.required' => 'Please select at least one attribute value.',
            'attribute_value_ids.array' => 'Invalid format for attribute values.',
            'attribute_value_ids.*.exists' => 'One or more selected attribute values are invalid.',

            'sku.unique' => 'This SKU is already in use.',
            'price.required' => 'Please enter the product price.',
            'price.numeric' => 'Price must be a number.',
            // 'qty.required' => 'Please specify the available quantity.',
            'min_order_qty.min' => 'Minimum order quantity must be at least 1.',
            'max_order_qty.gte' => 'Maximum order quantity must be greater than or equal to the minimum order quantity.',
            'is_track_stock.boolean' => 'Invalid stock tracking value.',
        ];
    }
}
