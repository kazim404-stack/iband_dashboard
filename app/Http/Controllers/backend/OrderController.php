<?php

namespace App\Http\Controllers\backend;

use App\DataTables\OrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }
    public function updateDeliveredAtDate(Order $order)
    {
        $order->update([
            'delivered_at' => Carbon::now()
        ]);
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }
    public function delete(Order $order)
    {
        $order->delete();
        return response()->json(['status' => 'success', 'message' => 'Order has been deleted successfully']);
    }
}
