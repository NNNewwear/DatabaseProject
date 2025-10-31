<?php

namespace App\Http\Controllers;

use App\Models\OrderHeader;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    public function index(OrderHeader $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $details = $order->details()->with('product')->get();
        return view('orders.details', compact('order','details'));
    }
}
