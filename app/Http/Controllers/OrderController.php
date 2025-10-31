<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderHeader;
use App\Models\OrderDetail;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 

class OrderController extends Controller
{
   
    /* ------------------------------ ORDER = CART ------------------------------ */
    // หมายเหตุ: โค้ดส่วนตะกร้า/เพิ่มลบจำนวน อาจมีในโปรเจกต์คุณอยู่แล้ว
    // ตรงไหนที่เคยอ้าง $order->details() ให้เปลี่ยนเป็น $order->orderDetails()

    // ตัวอย่างเมธอดเพิ่มสินค้าลงออเดอร์ (ถ้าโปรเจกต์คุณมีอยู่แล้ว ให้คง route/method เดิม แต่แก้เฉพาะ relationship)
    public function index()
    {
        $orders = OrderHeader::where('user_id', Auth::id())
            ->where('status', 'cart')              // <- สำคัญ
            ->with('orderDetails.product')
            ->latest('order_date')
            ->get();

        return view('fe.orders.index', compact('orders'));  // <- คนละวิวกับ history
    }

    public function addToOrder(Request $request, Product $product)
{
    $qty = max(1, (int) $request->input('qty', 1));

    // หา/สร้างออเดอร์ของ user ที่ยังเป็นตะกร้า
    $order = OrderHeader::firstOrCreate(
        ['user_id' => Auth::id(), 'status' => 'cart'],
        [
            'order_date'   => now(),
            
            'total_price'  => 0,
        ]
    );

    $orderId = $order->order_id; // primary key ตาม ERD

    // หา detail เดิมของสินค้านี้
    $detail = OrderDetail::where('order_id', $orderId)
        ->where('product_id', $product->product_id)
        ->first();

    if ($detail) {
        $newQty = $detail->total_amount + $qty;
        $detail->update([
            'total_amount' => $newQty,
            'total_price'  => $newQty * $product->price,
        ]);
    } else {
        OrderDetail::create([
            'order_id'     => $orderId,
            'product_id'   => $product->product_id,
            'total_amount' => $qty,
            'total_price'  => $qty * $product->price,
        ]);
    }

    // อัปเดตราคารวมของหัวออเดอร์
    $order->update([
        'total_price' => $order->orderDetails()->sum('total_price'),
    ]);

    // <<< อย่าใส่โค้ดใด ๆ หลังจากนี้ในระดับ class >>>
    return back()->with('success', 'เพิ่มสินค้าในออเดอร์แล้ว');
}


    // ปรับจำนวนสินค้าในออเดอร์ (ตัวอย่าง)
    public function updateQty(Request $request, OrderDetail $detail)
    {
        abort_if($detail->order->user_id !== Auth::id(), 403);

        $qty = max(1, (int) $request->input('qty', 1));
        $detail->update([
            'total_amount' => $qty,
            'total_price'  => $qty * $detail->product->price,
        ]);

        // อัปเดตราคารวมของหัวออเดอร์
        $order = $detail->order;
        $order->update([
            'total_price' => $order->orderDetails()->sum('total_price'),
        ]);

        return back()->with('success', 'อัปเดตจำนวนแล้ว');
    }

    // ลบรายการสินค้าในออเดอร์ (ตัวอย่าง)
    public function removeItem(OrderDetail $detail)
    {
        abort_if($detail->order->user_id !== Auth::id(), 403);

        $order = $detail->order;
        $detail->delete();

        $order->update([
            'total_price' => $order->orderDetails()->sum('total_price'),
        ]);

        return back()->with('success', 'ลบสินค้าแล้ว');
    }

    // ยืนยันสั่งซื้อ (เช็คเอาท์)
    /*public function checkout(Request $request)
    {
        $request->validate([
            'order_method' => 'required|in:cod,bank,card',
        ]);

        $order = OrderHeader::where('user_id', Auth::id())
            ->where('status', 'cart')
            ->with('orderDetails.product')
            ->first();

        if (!$order || $order->orderDetails->isEmpty()) {
            return back()->with('error', 'ยังไม่มีสินค้าในออเดอร์');
        }

        $total = $order->orderDetails()->sum('total_price');

        $order->update([
            'order_method' => $request->order_method,
            'order_date'   => now(),
            'status'       => 'placed',
            'total_price'  => $total,
        ]);

        return redirect()->route('orders.history')->with('success', 'สั่งซื้อสำเร็จ');
    }*/

    public function history()
    {
        $orders = OrderHeader::where('user_id', Auth::id())
            ->whereIn('status', ['delivering', 'placed', 'completed']) // ✅
            ->with('orderDetails.product')
            ->latest('order_date')
            ->get();

        return view('fe.orders.history', compact('orders'));
    }





    // แสดงรายละเอียดออเดอร์เดี่ยว
    public function show(OrderHeader $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        // โหลดความสัมพันธ์ชื่อที่ถูกต้อง
        $order->load('orderDetails.product');
        return view('orders.show', compact('order'));
    }

    public function decrementQty(Request $request, OrderDetail $detail)
    {
        abort_if($detail->order->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $remove = $data['qty'];
        $current = (int) $detail->total_amount;

        // คำนวณจำนวนใหม่
        $newQty = max(0, $current - $remove);

        if ($newQty === 0) {
            // ลบทั้งรายการถ้าจำนวนเหลือ 0
            $order = $detail->order;
            $detail->delete();
        } else {
            // อัปเดตจำนวนและราคารวมของรายการนี้
            $detail->update([
                'total_amount' => $newQty,
                'total_price'  => $newQty * $detail->product->price,
            ]);
            $order = $detail->order;
        }

        // อัปเดตยอดรวมของออเดอร์เสมอ
        $order->update([
            'total_price' => $order->orderDetails()->sum('total_price'),
        ]);

        return back()->with('success', 'อัปเดตจำนวนสินค้าแล้ว');
    }

    public function checkoutPage()
    {
        $order = OrderHeader::where('user_id', Auth::id())
            ->where('status', 'cart')
            ->with('orderDetails.product')
            ->first();

        if (!$order || $order->orderDetails->isEmpty()) {
            return back()->with('error', 'ยังไม่มีสินค้าในตะกร้า');
        }

        $order->update([
            'total_price' => $order->orderDetails()->sum('total_price'),
        ]);

        // ✅ ดึงบัตรของผู้ใช้คนนี้เท่านั้น
        $cards = Card::where('user_id', Auth::id())->orderBy('card_id')->get();

        // ถ้าไฟล์วิวอยู่ที่ resources/views/fe/orders/checkout.blade.php
        return view('fe.orders.checkout', compact('order', 'cards'));
    }

    // ยืนยันจากหน้า Checkout -> เปลี่ยนสถานะเป็น delivering
   public function placeOrder(Request $request)
{
    $request->validate([
        'email'       => 'required|email',
        'first_name'  => 'required|string|max:100',
        'last_name'   => 'nullable|string|max:100',
        'address'     => 'required|string|max:255',
        'phone'       => 'required|string|max:30',
        'card_id'     => [
            'required',
            \Illuminate\Validation\Rule::exists('cards', 'card_id')->where('user_id', \Illuminate\Support\Facades\Auth::id()),
        ],
    ]);

    $order = \App\Models\OrderHeader::where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->where('status', 'cart')
        ->with('orderDetails.product')
        ->first();

    if (!$order || $order->orderDetails->isEmpty()) {
        return redirect()->route('orders.checkout.page')->with('error', 'ตะกร้าว่าง');
    }

    $total = $order->orderDetails()->sum('total_price');

    $order->update([
        'order_date'   => now(),
        'status'       => 'delivering',
        'total_price'  => $total,
    ]);

    return redirect()
        ->route('orders.history')
        ->with('success', 'สั่งซื้อสำเร็จ กำลังจัดส่ง')
        ->with('order_success', [
            'order_id' => $order->order_id,
            'total'    => $total,
            'status'   => 'delivering',
        ]);
}

}
