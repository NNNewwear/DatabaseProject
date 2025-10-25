<?php

namespace App\Http\Controllers;

use App\Models\OrderHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = OrderHeader::where('user_id', Auth::id())->with('orderDetails.product')->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'order_method' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        $order = OrderHeader::create([
            'user_id' => Auth::id(),
            'card_id' => $request->card_id,
            'order_date' => now(),
            'order_method' => $request->order_method,
            'status' => 'pending',
            'total_price' => $request->total_price,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderHeader $orderHeader)
    {
        if ($order->user_id != Auth::id()) abort(403);
        $order->load('orderDetails.product');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderHeader $orderHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderHeader $orderHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderHeader $orderHeader)
    {
        //
    }
}
