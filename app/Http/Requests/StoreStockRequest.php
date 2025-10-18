<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
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
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'qty' => ['required', 'integer', 'min:0'],
            'stock_status' => ['required', 'in:in_stock,out_of_stock'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_variant_id.required' => 'The product variant field is required.',
            'product_variant_id.exists' => 'The selected product variant does not exist.',

            'currency_id.required' => 'The currency field is required.',
            'currency_id.exists' => 'The selected currency does not exist.',

            'qty.required' => 'Please enter the quantity.',
            'qty.integer' => 'Quantity must be a valid number.',
            'qty.min' => 'Quantity cannot be less than 0.',

            'stock_status.required' => 'Stock status is required.',
            'stock_status.in' => 'Stock status must be either in_stock or out_of_stock.',
        ];
    }
}
