<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralSettingRequest extends FormRequest
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
            'site_name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'whatsapp' => 'nullable|string|max:255',
            'youtube' => 'nullable|url',
            'telegram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'x' => 'nullable|url',
        ];
    }
    public function messages(): array
    {
        return [
            'site_name.required'  => 'The site name is required.',
            'site_name.string'    => 'The site name must be a valid text.',
            'site_name.max'       => 'The site name may not be greater than 255 characters.',

            'logo.required'       => 'A logo is required.',
            'logo.image'          => 'The logo must be an image.',
            'logo.mimes'          => 'The logo must be a file of type: jpg, jpeg, png, svg, or webp.',
            'logo.max'            => 'The logo size may not exceed 2MB.',

            'facebook.url'        => 'Please enter a valid Facebook URL.',
            'instagram.url'       => 'Please enter a valid Instagram URL.',
            'whatsapp.string'     => 'The WhatsApp field must be text.',
            'whatsapp.max'        => 'The WhatsApp field may not be greater than 255 characters.',
            'youtube.url'         => 'Please enter a valid YouTube URL.',
            'telegram.url'        => 'Please enter a valid Telegram URL.',
            'linkedin.url'        => 'Please enter a valid LinkedIn URL.',
            'x.url'               => 'Please enter a valid X (Twitter) URL.',
        ];
    }
}
