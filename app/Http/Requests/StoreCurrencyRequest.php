<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
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
            'code' => 'required|string|max:10|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_default' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Currency code is required.',
            'code.unique' => 'This currency code already exists.',
            'code.max' => 'Currency code must not exceed 10 characters.',

            'symbol.required' => 'Currency symbol is required.',
            'symbol.max' => 'Currency symbol must not exceed 10 characters.',

            'exchange_rate.required' => 'Exchange rate is required.',
            'exchange_rate.numeric' => 'Exchange rate must be a valid number.',
            'exchange_rate.min' => 'Exchange rate cannot be negative.',

            'is_default.boolean' => 'Default status must be true or false.',
        ];
    }
}
