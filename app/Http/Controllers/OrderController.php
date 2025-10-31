<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderHeader;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
   

    /* ------------------------------ ORDER = CART ------------------------------ */

    // แสดงตะกร้า (Order ที่ status = 'cart')
    public function index()
    {
        $order = OrderHeader::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'cart'],
            ['order_date' => now(), 'total_price' => 0]
        );

        $details = $order->details()->with('product')->get();
        $subtotal = $details->sum('total_price');

        return view('orders.cart', compact('order', 'details', 'subtotal'));
    }

    // เพิ่มสินค้าเข้า "Order (cart)"
    public function addProduct(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('qty', 1));

        $order = OrderHeader::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'cart'],
            ['order_date' => now(), 'total_price' => 0]
        );

        $detail = $order->details()->where('product_id', $product->product_id)->first();

        if ($detail) {
            $newQty = $detail->total_amount + $qty;
            $detail->update([
                'total_amount' => $newQty,
                'total_price'  => $newQty * $product->price,
            ]);
        } else {
            OrderDetail::create([
                'order_id'     => $order->order_id,
                'product_id'   => $product->product_id,
                'total_amount' => $qty,
                'total_price'  => $qty * $product->price,
            ]);
        }

        // อัปเดตราคารวม
        $order->update([
            'total_price' => $order->details()->sum('total_price'),
        ]);

        return back()->with('success', 'เพิ่มสินค้าในออเดอร์แล้ว');
    }

    // ปรับจำนวนสินค้า
    public function updateProduct(Request $request, OrderDetail $detail)
    {
        $qty = (int) $request->validate(['qty' => 'required|integer|min:1'])['qty'];
        $product = $detail->product;
        if ($product->stock_quantity < $qty) {
            return back()->with('error', 'สต็อกไม่พอ');
        }

        $detail->update([
            'total_amount' => $qty,
            'total_price'  => $qty * $product->price,
        ]);

        $detail->order->update(['total_price' => $detail->order->details()->sum('total_price')]);

        return back()->with('success', 'อัปเดตจำนวนแล้ว');
    }

    // ลบสินค้าออกจากออเดอร์
    public function removeProduct(OrderDetail $detail)
    {
        $order = $detail->order;
        $detail->delete();
        $order->update(['total_price' => $order->details()->sum('total_price')]);

        return back()->with('success', 'นำสินค้าออกจากออเดอร์แล้ว');
    }

    /* ------------------------------ CHECKOUT ------------------------------ */

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'order_method' => 'required|in:cod,card,bank',
            'card_id' => 'nullable|integer',
        ]);

        $order = OrderHeader::where('user_id', Auth::id())
            ->where('status', 'cart')
            ->with('details.product')
            ->firstOrFail();

        if ($order->details->isEmpty()) {
            return back()->with('error', 'ไม่มีสินค้าในออเดอร์');
        }

        // ตรวจสต็อก
        foreach ($order->details as $d) {
            if ($d->product->stock_quantity < $d->total_amount) {
                return back()->with('error', "สินค้า {$d->product->name} สต็อกไม่พอ");
            }
        }

        DB::transaction(function () use ($order, $data) {
            foreach ($order->details as $d) {
                $d->product->decrement('stock_quantity', $d->total_amount);
            }

            $order->update([
                'status' => 'confirmed',
                'order_date' => now(),
                'order_method' => $data['order_method'],
                'card_id' => $data['card_id'] ?? null,
            ]);
        });

        return redirect()->route('orders.show', $order)->with('success', 'สั่งซื้อสำเร็จ');
    }

    /* ------------------------------ แสดงออเดอร์ที่ยืนยันแล้ว ------------------------------ */

    public function history()
    {
        $orders = OrderHeader::where('user_id', Auth::id())
            ->where('status', '!=', 'cart')
            ->latest('order_date')
            ->get();

        return view('orders.history', compact('orders'));
    }

    public function show(OrderHeader $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('details.product');
        return view('orders.show', compact('order'));
    }
}
