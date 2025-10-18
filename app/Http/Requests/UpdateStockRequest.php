<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
            'product_variant_id' => ['sometimes', 'exists:product_variants,id'],
            'qty' => ['sometimes', 'integer', 'min:0'],
            'stock_status' => ['sometimes', 'in:in_stock,out_of_stock'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_variant_id.exists' => 'The selected product variant does not exist.',

            'qty.integer' => 'Quantity must be a valid number.',
            'qty.min' => 'Quantity cannot be less than 0.',

            'stock_status.in' => 'Stock status must be either in_stock or out_of_stock.',
        ];
    }
}
