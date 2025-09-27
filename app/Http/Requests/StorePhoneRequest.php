<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhoneRequest extends FormRequest
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
            'contact_id' => "required|exists:contacts,id",
            'phone_number' => "required|string|max:20|min:10"
        ];
    }
    public function messages(){
        return [
            'contact_id.required' => "The contact is required.",
            'contact_id.exists' => "The selected contact dose not exist.",

            'phone_number.required' => "The phone number is required",
            'phone_number.string' => "The phone number must be a valid string",
            'phone_number.max' => "The phone number may not be greater than 20 characters",
            'phone_number.min' => "The phone number may not be less than 10 characters",
        ];
    }
}
