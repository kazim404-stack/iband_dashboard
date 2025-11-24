<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Coupon;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            // Calculate total items before discount
            $total = collect($data['items'])->sum(function ($item) {
                return $item['qty'] * $item['price_at_purchase'];
            });
            // if coupon exist then apply
            $total_price = $total;
            if (!empty($data['coupon_id'])) {
                $coupon = Coupon::find($data['coupon_id']);
                if ($coupon) {
                    // Apply discount
                    if ($coupon->checkIfValid()) {
                        $total_price = $total - ($total * $coupon->discount / 100);
                        // make sure the price not become negative
                        $total_price = max($total_price, 0);
                    }
                }
            }
            //   create order
            $order = Order::create([
                'user_id' => $data['user_id'],
                'coupon_id' => $data['coupon_id'] ?? null,
                'total_price' => $total_price,
                'status' => 0, // pending
                'delivered_at' => null
            ]);

            // make order items
            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'product_variant_id' => $item['product_variant_id'],
                    'qty' => $item['qty'],
                    'price_at_purchase' => $item['price_at_purchase'],
                    'total' => $item['qty'] * $item['price_at_purchase'],
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order created successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // user orders
    public function userOrders(Request $request)
    {
        $user = auth()->user();
        // ---- Language (Query → Session → Default) ----
        $lang = $request->query('lang')
            ?? session('lang')
            ?? app()->getLocale();

        // ---- Currency (Query → Session → USD) ----
        $currencyCode = $request->query('currency_code')
            ?? session('currency_code')
            ?? 'USD';
        $selectedCurrency = Currency::where('code', $currencyCode)->first();
        $defaultCurrency  = Currency::where('is_default', 1)->first();

        $defaultRate = $defaultCurrency?->exchange_rate ?? 1.0;
        $selectedRate = $selectedCurrency?->exchange_rate ?? 1.0;
        $exchangeRate = $selectedRate / $defaultRate;

        // ---- Orders ----
        $orders = $user->orders()->with([
            'items.productVariant.attributeValues.attribute',
            'items.productVariant.product',
            'items.productVariant.stocks',
            'items.productVariant.reviews.user',
        ])->get();

        $data = $orders->map(function ($order) use ($lang, $exchangeRate, $selectedCurrency) {
            return [
                'order_id' => $order->id,
                'status' => $order->status,
                'total' => round($order->total * $exchangeRate, 2),
                'currency' => [
                    'code' => $selectedCurrency->code ?? 'USD',
                    'symbol' => $selectedCurrency->symbol ?? '$'
                ],
                'items' => $order->items->map(function ($item) use ($lang, $exchangeRate, $selectedCurrency) {
                    $variant = $item->productVariant;
                    $product = $variant->product;
                    $convertedPrice = $variant->price * $exchangeRate;

                    return [
                        'item_id' => $item->id,
                        'qty' => $item->qty,
                        'price' => round($convertedPrice, 2),
                        'product' => [
                            'id' => $product->id,
                            'name' => $product->getTranslation('name', $lang),
                        ],
                        'variant' => [
                            'id' => $variant->id,
                            'sku' => $variant->sku,
                            'price' => round($convertedPrice, 2),
                            'attributes' => $variant->attributeValues
                                ->groupBy(fn($v) => $v->attribute->id)
                                ->map(
                                    fn($group) =>
                                    $group->map(fn($v) => $v->getTranslation('value', $lang))
                                        ->values()
                                ),
                        ],
                        'reviews' => $variant->reviews->map(function ($review) use ($lang) {
                            return [
                                'review_id' => $review->id,
                                'rating' => $review->rating,
                                'comment' => $review->getTranslation('comment', $lang),
                                'user' => [
                                    'id' => $review->user->id,
                                    'name' => $review->user->name,
                                ]
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => 'success',
            'lang' => $lang,
            'currency' => $currencyCode,
            'data' => $data,
        ]);
    }
}
