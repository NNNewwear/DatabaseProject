<x-app-layout>
<div class="mx-auto max-w-6xl px-4">

  {{-- ✅ Success Popup --}}
  @if (session('order_success'))
    @php($s = session('order_success'))
    <div id="order-success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-2xl shadow-xl w-[90%] max-w-md p-6">
        <div class="text-center">
          <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
              <path d="M22 4 12 14.01l-3-3"/>
            </svg>
          </div>
          <h2 class="text-xl font-semibold mb-1">ชำระเงินเสร็จสิ้น</h2>
          <p class="text-gray-600 mb-4">
            ออเดอร์ #{{ $s['order_id'] }} สถานะ 
            <span class="font-semibold text-blue-700">{{ strtoupper($s['status']) }}</span><br>
            ยอดรวม ฿{{ number_format($s['total'], 2) }}
          </p>

          {{-- ปุ่มกดไปหน้า history (หน้านี้อยู่แล้วก็ให้ปิด popup) --}}
          <a href="{{ route('orders.history') }}"
             class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 inline-block">
             ดูสถานะการจัดส่ง
          </a>
        </div>
      </div>
    </div>
  @endif

  {{-- ✅ เนื้อหาหลักของหน้า history --}}
  <h1 class="text-2xl font-semibold text-center my-6">คำสั่งซื้อของฉัน (กำลังจัดส่ง)</h1>

  @if($orders->isEmpty())
    <div class="rounded-2xl border bg-white p-6 text-center text-gray-500">
      ยังไม่มีคำสั่งซื้อที่กำลังจัดส่ง
    </div>
  @else
    @foreach($orders as $order)
      <div class="mb-6 bg-gray-100 p-5 rounded-2xl shadow-sm">
        <div class="flex justify-between items-start mb-3">
          <div>
            <h2 class="text-lg font-semibold">Order #{{ $order->order_id }}</h2>
            <p class="text-gray-600 text-sm">
              วันที่สั่ง: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
            </p>
          </div>
          <div class="text-right">
            <p class="text-gray-700">ยอดรวม</p>
            <p class="text-lg font-bold text-blue-700">
              ฿{{ number_format($order->total_price, 2) }}
            </p>
          </div>
        </div>

        {{-- รายการสินค้าใน order --}}
        <div class="bg-white rounded-xl p-4 space-y-2">
          @foreach($order->orderDetails as $d)
            <div class="flex justify-between">
              <div>
                <span class="font-medium">{{ $d->product->name ?? '-' }}</span>
                <span class="text-gray-500">× {{ $d->total_amount }}</span>
              </div>
              <div class="text-gray-700">
                ฿{{ number_format($d->total_price, 2) }}
              </div>
            </div>
          @endforeach
        </div>

        {{-- สถานะ --}}
        <div class="flex justify-end mt-4">
          <span class="px-5 py-2 bg-blue-600 text-white rounded-full">
            {{ strtoupper($order->status) }}
          </span>
        </div>
      </div>
      <hr class="my-4 border-gray-300">
    @endforeach
  @endif

</div>
</x-app-layout>
