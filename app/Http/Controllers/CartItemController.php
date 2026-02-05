<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
// use App\Http\Requests\AddToCart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartItemController extends Controller
{
    //
    // Add or update cart item
    public function add(AddToCartRequest $request)
    {
        $userId = Auth::id();

        // Determine session ID for guest
        if (! $userId) {
            // Priority: 1. Guest session ID, 2. Laravel session, 3. Generate new
            $sessionId = $request->guest_session_id ?? session()->getId();

            // If no session exists, create one based on guest ID
            if (! $sessionId) {
                $sessionId = 'guest_'.uniqid();
            }

            // Ensure we have a consistent session
            if (! session()->isStarted()) {
                session()->setId($sessionId);
                session()->start();
            }
        } else {
            $sessionId = null; // Authenticated users don't need session
        }

        Log::info('Guest cart debug:', [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'guest_session_id' => $request->guest_session_id,
            'request_session_id' => $request->session_id,
        ]);

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
                'price' => $request->price,
                'currency_id' => $request->currency_id,
            ]
        );

        // Get current cart count for this guest/user
        $cartCount = CartItem::where('user_id', $userId)
            ->where('session_id', $userId ? null : $sessionId)
            ->sum('quantity');

        return response()->json([
            'success' => true,
            'cartItem' => $cartItem,
            'cart_count' => $cartCount,
            'session_id' => $sessionId,
        ]);
    }
    // public function add(AddToCartRequest $request)
    // {
    //     Log::info('Cart add request:', $request->all());
    //      $userId = Auth::id();

    //     $sessionId = session()->getId();

    //     $cartItem = CartItem::updateOrCreate(
    //         [
    //             'user_id' => $userId,
    //             'session_id' => $userId ? null : $sessionId,
    //             'product_id' => $request->product_id
    //         ],
    //         [
    //             'quantity' => $request->quantity,
    //             'price' => $request->price,
    //             'currency_id' => $request->currency_id
    //         ]
    //     );

    //     return response()->json(['success' => true, 'cartItem' => $cartItem]);
    // }

    // Get all cart items for current user/session
    // public function index(Request $request)
    // {
    //     $userId = Auth::id();
    //     $lang = $request->query('lang', app()->getLocale());

    //     $sessionId = session()->getId();

    //     $cartItems = CartItem::with('product')
    //         ->where(function ($q) use ($userId, $sessionId) {
    //             $q->where('user_id', $userId)
    //               ->orWhere('session_id', $sessionId);
    //         })->get();

    //     return response()->json($cartItems);
    // }
    public function index(Request $request)
    {
        $lang = $request->query('lang', app()->getLocale());
        $currencyCode = session('currency_code', 'USD');

        $selectedCurrency = \App\Models\Currency::where('code', $currencyCode)->first();
        $defaultCurrency = \App\Models\Currency::where('is_default', 1)->first();

        $defaultRate = $defaultCurrency ? $defaultCurrency->exchange_rate : 1.0;
        $selectedRate = $selectedCurrency ? $selectedCurrency->exchange_rate : 1.0;
        $exchangeRate = $selectedRate / $defaultRate;

        $userId = Auth::id();
        $sessionId = session()->getId();

        // 1️⃣ Get cart items
        $cartItems = CartItem::with('product.productVariants.stocks.currency')
            ->where(function ($q) use ($userId, $sessionId) {
                $q->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            })->get();

        // 2️⃣ Map cart items
        $cartData = $cartItems->map(function ($cartItem) use ($lang, $exchangeRate, $selectedCurrency) {
            $product = $cartItem->product;

            if (! $product) {
                return null; // skip if product deleted
            }

            // Calculate total stock status
            $totalStockQty = $product->productVariants->flatMap(fn ($v) => $v->stocks)->sum('qty');
            $productStockStatus = $totalStockQty > 0 ? 'in_stock' : 'out_of_stock';

            // Map product variants
            $productVariants = $product->productVariants->map(function ($variant) use ($exchangeRate, $selectedCurrency, $lang) {
                $convertedPrice = $variant->price * $exchangeRate;
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
                        'code' => $selectedCurrency?->code ?? 'USD',
                        'symbol' => $selectedCurrency?->symbol ?? '$',
                    ],
                    'qty' => $stockQty,
                    'stock_status' => $stockStatus,
                    'attribute_values' => $variant->attributeValues
                        ->groupBy(fn ($value) => $value->attribute->id)
                        ->map(fn ($group) => $group->map(fn ($v) => $v->getTranslation('value', $lang))->values()),
                ];
            });

            return [
                // ✅ Cart item fields
                'id' => $cartItem->id,
                'user_id' => $cartItem->user_id,
                'session_id' => $cartItem->session_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => round($cartItem->price * $exchangeRate, 2),
                'total_price' => round($cartItem->price * $exchangeRate, 2) * $cartItem->quantity,
                'currency' => $selectedCurrency?->symbol ?? '$',
                // ✅ Product details
                'product' => [
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
                    'product_images' => $product->productImages->map(fn ($image) => [
                        'id' => $image->id,
                        'image' => $image->image,
                    ]),
                    'product_variants' => $productVariants,
                ],
            ];
        })->filter(); // remove nulls if product deleted

        return response()->json($cartData);
    }

    // Remove item
    public function remove($id)
    {
        CartItem::destroy($id);

        return response()->json(['success' => true]);
    }

    // Update quantity
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        // $cartItem->price = $request->price;
        // $cartItem->currency_id = $request->currency;
        $cartItem->save();

        return response()->json(['success' => true, 'cartItem' => $cartItem]);
    }

    // Merge guest cart into user cart after login
    public function mergeGuestCart()
    {
        $userId = Auth::id();

        $sessionId = session()->getId();

        $guestCart = CartItem::where('session_id', $sessionId)->get();

        foreach ($guestCart as $item) {
            CartItem::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $item->product_id],
                ['quantity' => $item->quantity, 'quantity' => $item->quantity],
                ['price' => $item->price, 'price' => $item->price]
            );
            $item->delete();
        }

        return response()->json(['success' => true]);
    }
}
