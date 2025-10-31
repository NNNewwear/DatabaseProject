<?php

namespace App\Http\Controllers;

use App\Models\OrderHeader;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    public function index(OrderHeader $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        // ใช้ชื่อความสัมพันธ์ให้ตรงกับโมเดล: orderDetails
        $details = $order->orderDetails()->with('product')->get();
        return view('orders.details', compact('order', 'details'));
    }
}
