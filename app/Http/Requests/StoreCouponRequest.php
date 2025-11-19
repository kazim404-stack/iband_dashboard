<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
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
            'name'        => 'required|string|max:255|unique:coupons,name',
            'discount'    => 'required|integer|min:1|max:100',
            'valid_until' => 'required|date',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'   => 'Coupon name is required.',
            'name.string'     => 'Coupon name must be a valid string.',
            'name.max'        => 'Coupon name cannot be longer than 255 characters.',
            'name.unique'     => 'This coupon name already exists.',

            'discount.required' => 'Discount amount is required.',
            'discount.integer'  => 'Discount must be a number.',
            'discount.min'      => 'Discount must be at least 1%.',
            'discount.max'      => 'Discount cannot be more than 100%.',

            'valid_until.required' => 'Expiration date is required.',
            'valid_until.date'     => 'Expiration date must be a valid date (Y-m-d).',
        ];
    }
}
