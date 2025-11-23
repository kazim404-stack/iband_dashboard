<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Coupon;
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

}
