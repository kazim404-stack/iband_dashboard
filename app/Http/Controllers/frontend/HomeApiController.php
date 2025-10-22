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
    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|max:10|exists:currencies,code'
        ]);
        session(['currency_code' => strtoupper($request->currency_code)]);

        return response()->json([
            'status' => 'success',
            'currency_code' => session('currency_code')
        ]);
    }
    public function getCurrency()
    {
        return response()->json([
            'currency_code' => session('currency_code', 'USD'),
        ]);
    }
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

        $currencyCode = session('currency_code', 'USD');

        $selectedCurrency = \App\Models\Currency::where('code', $currencyCode)->first();
        $defaultCurrency  = \App\Models\Currency::where('is_default', 1)->first();

        $defaultRate = $defaultCurrency ? $defaultCurrency->exchange_rate : 1.0;
        $selectedRate = $selectedCurrency ? $selectedCurrency->exchange_rate : 1.0;

        $exchangeRate = $selectedRate / $defaultRate;

        $products = Product::where('status', 1)
            ->with([
                'category',
                'productImages',
                'productVariants.attributeValues.attribute',
                'productVariants.stocks.currency'
            ])
            ->get();
        $data = $products->map(function ($product) use ($lang, $exchangeRate, $selectedCurrency) {
            $totalStockQty = $product->productVariants->flatMap(fn($v) => $v->stocks)->sum('qty');

            $productStockStatus = $totalStockQty > 0 ? 'in_stock' : 'out_of_stock';

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
                'stock_status' => $productStockStatus,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->getTranslation('name', $lang),
                ] : null,
                'product_images' => $product->productImages->map(fn($image) => [
                    'id' => $image->id,
                    'image' => $image->image,
                ]),
                'product_variants' => $product->productVariants->map(function ($variant) use ($lang, $exchangeRate, $selectedCurrency) {

                    $convertedPrice = $variant->price * $exchangeRate;
                    $convertedComparePrice = $variant->compare_price ? $variant->compare_price * $exchangeRate : null;
                    $convertedCostPrice = $variant->cost_price ? $variant->cost_price * $exchangeRate : null;

                    $stockQty = $variant->stocks->sum('qty');
                    $stockStatus = $stockQty > 0 ? 'in_stock' : 'out_of_stock';

                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'barcode' => $variant->barcode,
                        'price' => round($convertedPrice, 2),
                        'compare_price' => $convertedComparePrice ? round($convertedComparePrice, 2) : null,
                        'cost_price' => $convertedCostPrice ? round($convertedCostPrice, 2) : null,
                        'currency' => [
                            'code' => $selectedCurrency ? $selectedCurrency->code : 'USD',
                            'symbol' => $selectedCurrency ? $selectedCurrency->symbol : '$',
                        ],
                        'qty' => $stockQty,
                        'stock_status' => $stockStatus,
                        'attribute_values' => $variant->attributeValues
                            ->groupBy(fn($value) => $value->attribute->getTranslation('name', $lang))
                            ->map(function ($group) use ($lang) {
                                return $group->map(fn($value) => $value->getTranslation('value', $lang))->values();
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

        $currencyCode = session('currency_code', 'USD');

        $selectedCurrency = \App\Models\Currency::where('code', $currencyCode)->first();
        $defaultCurrency  = \App\Models\Currency::where('is_default', 1)->first();

        $defaultRate = $defaultCurrency ? $defaultCurrency->exchange_rate : 1.0;
        $selectedRate = $selectedCurrency ? $selectedCurrency->exchange_rate : 1.0;
        $exchangeRate = $selectedRate / $defaultRate;

        $category = Category::with([
            'products' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'productImages',
                        'productVariants.attributeValues.attribute',
                        'productVariants.stocks.currency'
                    ]);
            }
        ])->find($categoryId);

        if (!$category || $category->products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found for this category'
            ], 404);
        }

        $categoryData = [
            'id' => $category->id,
            'name' => $category->getTranslation('name', $lang),
            'products' => $category->products->map(function ($product) use ($lang, $exchangeRate, $selectedCurrency) {


                $totalStockQty = $product->productVariants->flatMap(fn($v) => $v->stocks)->sum('qty');
                $productStockStatus = $totalStockQty > 0 ? 'in_stock' : 'out_of_stock';

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
                    'stock_status' => $productStockStatus, // ✅ وضعیت کلی محصول
                    'product_images' => $product->productImages->map(fn($image) => [
                        'id' => $image->id,
                        'image' => $image->image,
                    ]),
                    'product_variants' => $product->productVariants->map(function ($variant) use ($lang, $exchangeRate, $selectedCurrency) {

                        $convertedPrice = $variant->price * $exchangeRate;
                        $convertedComparePrice = $variant->compare_price ? $variant->compare_price * $exchangeRate : null;
                        $convertedCostPrice = $variant->cost_price ? $variant->cost_price * $exchangeRate : null;

                        $stockQty = $variant->stocks->sum('qty');
                        $stockStatus = $stockQty > 0 ? 'in_stock' : 'out_of_stock';

                        return [
                            'id' => $variant->id,
                            'sku' => $variant->sku,
                            'barcode' => $variant->barcode,
                            'price' => round($convertedPrice, 2),
                            'compare_price' => $convertedComparePrice ? round($convertedComparePrice, 2) : null,
                            'cost_price' => $convertedCostPrice ? round($convertedCostPrice, 2) : null,
                            'currency' => [
                                'code' => $selectedCurrency ? $selectedCurrency->code : 'USD',
                                'symbol' => $selectedCurrency ? $selectedCurrency->symbol : '$',
                            ],
                            'qty' => $stockQty,
                            'stock_status' => $stockStatus,
                            'attribute_values' => $variant->attributeValues
                                ->groupBy(fn($value) => $value->attribute->getTranslation('name', $lang))
                                ->map(function ($group) use ($lang) {
                                    return $group->map(fn($value) => $value->getTranslation('value', $lang))->values();
                                }),
                        ];
                    }),
                ];
            }),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $categoryData
        ], 200);
    }
}
