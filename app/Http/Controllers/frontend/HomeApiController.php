<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeApiController extends Controller
{
    public function slider(Request $request)
    {
        $lang = $request->query('lang', app()->getLocale());

        $sliders = Slider::with(['sliderImages' => function ($query) use ($lang) {
            $query->where('type', $lang);
        }])->where('status', 1)->get();

        $data = $sliders->map(function ($slider) use ($lang) {
            return [
                'id' => $slider->id,
                'title' => $slider->getTranslation('title', $lang),
                'description' => $slider->getTranslation('description', $lang),
                'sliderImages' => $slider->sliderImages->map(function ($sliderImage) {
                    return [
                        'id' => $sliderImage->id,
                        'image' => $sliderImage->image,
                    ];
                })
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
    public function generalSetting(Request $request)
    {
        $lang = $request->query('lang', app()->getLocale());
        $generalSetting = GeneralSetting::with(['contacts.phones'])->first();

        $data = [
            'id' => $generalSetting->id,
            'site_name' => $generalSetting->site_name,
            'logo' => $generalSetting->logo,
            'facebook' => $generalSetting->facebook,
            'instagram' => $generalSetting->instagram,
            'whatsapp' => $generalSetting->whatsapp,
            'telegram' => $generalSetting->telegram,
            'twitter' => $generalSetting->twitter,
            'youtube' => $generalSetting->youtube,
            'x' => $generalSetting->x,
            'linkedin' => $generalSetting->linkedin,
            'contacts' => $generalSetting->contacts->map(function ($contact) use ($lang) {
                return [
                    'state' => $contact->getTranslation('state', $lang),
                    'address' => $contact->getTranslation('address', $lang),
                    'email' => $contact->email,
                    'is_primary' => $contact->is_primary,
                    'phones' => $contact->phones->map(function ($phone) {
                        return [
                            'id' => $phone->id,
                            'phone_number' => $phone->phone_number,
                        ];
                    })
                ];
            })
        ];
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

}
