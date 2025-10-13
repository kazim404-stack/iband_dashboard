<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\Product;
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
    public function category(Request $request)
    {
        $lang = $request->query('lang', app()->getLocale());
        $categories = Category::all();
        $data = $categories->map(function ($category) use ($lang) {
            return [
                'id' => $category->id,
                'name' => $category->getTranslation('name', $lang),
                'parent_id' => $category->parent_id,
                'image' => $category->image,
                'description' => $category->getTranslation('description', $lang),
                'slug' => $category->slug,
                'sort_order' => $category->sort_order,
                'status' => $category->status,
            ];
        });
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function product(Request $request)
    {
        $lang = $request->query('lang', app()->getLocale());

        $products = Product::with([
            'category',
            'productImages',
            'productVariants.attributeValues.attribute'
        ])->where('status', 1)->get();

        $data = $products->map(function ($product) use ($lang) {
            return [
                'id' => $product->id,
                'name' => $product->getTranslation('name', $lang),
                'short_description' => $product->getTranslation('short_description', $lang),
                'long_description' => $product->getTranslation('long_description', $lang),
                'sku' => $product->sku,
                'type' => $product->type,
                'weight' => $product->weight,
                'dimensions' => [
                    'length' => $product->length,
                    'width' => $product->width,
                    'height' => $product->height,
                ],
                'status' => $product->status,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->getTranslation('name', $lang),
                ] : null,
                'product_images' => $product->productImages->map(fn($image) => [
                    'id' => $image->id,
                    'image' => $image->image,
                ]),
                'product_variants' => $product->productVariants->map(function ($variant) use ($lang) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'barcode' => $variant->barcode,
                        'price' => $variant->price,
                        'compare_price' => $variant->compare_price,
                        'cost_price' => $variant->cost_price,
                        'qty' => $variant->qty,
                        'attribute_values' => $variant->attributeValues->map(function ($value) use ($lang) {
                            return [
                                'id' => $value->id,
                                'value' => $value->getTranslation('value', $lang),
                                'attribute' => [
                                    'id' => $value->attribute->id,
                                    'name' => $value->attribute->getTranslation('name', $lang),
                                ],
                            ];
                        }),
                    ];
                }),
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function productFilterByCatId(Request $request, $categoryId)
    {
        $lang = $request->query('lang', app()->getLocale());

        $products = Product::with([
            'category',
            'productImages',
            'productVariants.attributeValues.attribute'
        ])->where('category_id', $categoryId)
            ->where('status', 1)
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found for this category'
            ], 404);
        }

        $data = $products->map(function ($product) use ($lang) {
            return [
                'id' => $product->id,
                'name' => $product->getTranslation('name', $lang),
                'short_description' => $product->getTranslation('short_description', $lang),
                'long_description' => $product->getTranslation('long_description', $lang),
                'sku' => $product->sku,
                'type' => $product->type,
                'weight' => $product->weight,
                'dimensions' => [
                    'length' => $product->length,
                    'width' => $product->width,
                    'height' => $product->height,
                ],
                'status' => $product->status,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->getTranslation('name', $lang),
                ] : null,
                'product_images' => $product->productImages->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'image' => $image->image,
                    ];
                }),
                'product_variants' => $product->productVariants->map(function ($variant) use ($lang) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'barcode' => $variant->barcode,
                        'price' => $variant->price,
                        'compare_price' => $variant->compare_price,
                        'cost_price' => $variant->cost_price,
                        'qty' => $variant->qty,
                        'attribute_values' => $variant->attributeValues->map(function ($value) use ($lang) {
                            return [
                                'id' => $value->id,
                                'value' => $value->getTranslation('value', $lang),
                                'attribute' => [
                                    'id' => $value->attribute->id,
                                    'name' => $value->attribute->getTranslation('name', $lang),
                                ],
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
