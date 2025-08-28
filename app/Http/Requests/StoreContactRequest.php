<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'general_setting_id' => 'required|exists:general_settings,id',
            'email'              => 'required|email|max:255|unique:contacts,email',
            'state'              => 'required|array',
            'state.*'            => 'required|string|max:255',
            'address'            => 'required|array',
            'address.*'          => 'required|string|max:255',
            'is_primary'         => 'required|in:0,1',
        ];
    }


    public function messages(): array
    {
        return [
            'general_setting_id.required' => 'Please select a general setting.',
            'general_setting_id.exists'   => 'Selected general setting does not exist.',

            'email.required'              => 'Email is required.',
            'email.email'                 => 'Please enter a valid email address.',
            'email.max'                   => 'Email may not exceed 255 characters.',
            'email.unique'                => 'This email is already taken.',

            'state.required'              => 'State is required.',
            'state.array'                 => 'State must be an array.',
            'state.*.required'            => 'Each state field is required.',
            'state.*.string'              => 'Each state must be a valid text.',
            'state.*.max'                 => 'Each state may not exceed 255 characters.',

            'address.required'            => 'Address is required.',
            'address.array'               => 'Address must be an array.',
            'address.*.required'          => 'Each address field is required.',
            'address.*.string'            => 'Each address must be a valid text.',
            'address.*.max'               => 'Each address may not exceed 255 characters.',

            'is_primary.required'               => 'Is Primary is required.',
            'is_primary.in'               => 'Is Primary must be either 0 or 1.',
        ];
    }
}
