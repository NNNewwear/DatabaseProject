<x-app-layout>
<div class="mx-auto max-w-6xl px-4">
  <h1 class="text-xl font-semibold mb-4">คำสั่งซื้อของฉัน</h1>

  @forelse($orders as $o)
    @continue($o->status !== 'cart') 
    <div class="mb-3 rounded-2xl border bg-white p-4">
      <div class="flex items-center justify-between mb-2">
        <div>
          <div class="font-semibold">Order #{{ $o->order_id }}</div>
          <div class="text-sm text-gray-500">
            {{ \Illuminate\Support\Carbon::parse($o->order_date)->format('d M Y') }} • {{ strtoupper($o->status) }}
          </div>
        </div>

        {{-- ✅ ใส่คำว่า "ยอดรวม" --}}
        <div class="font-semibold">
          <span class="text-gray-500 mr-2">ยอดรวม</span>
          ฿{{ number_format($o->total_price,2) }}
        </div>
      </div>

      {{-- ✅ แสดงสินค้า + ปุ่มลบแต่ละรายการ --}}
      <ul class="ml-4 text-gray-700 space-y-2">
        @foreach ($o->orderDetails as $detail)
          <li class="flex items-start justify-between gap-3">
            <div class="pt-1">
              • {{ $detail->product->name ?? 'สินค้าถูกลบ' }}
              × {{ $detail->total_amount }}
              (฿{{ number_format($detail->total_price, 2) }})
            </div>

            <div class="flex items-center gap-2">
              {{-- ฟอร์มลบจำนวนบางส่วน --}}
              <form action="{{ route('orders.detail.decrement', $detail->id) }}" method="POST" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <input type="number" name="qty"
                      min="1" max="{{ $detail->total_amount }}"
                      value="1"
                      class="w-20 border rounded px-2 py-1 text-right">
                <button class="border px-3 py-1 rounded-lg">
                  ลบจำนวน
                </button>
              </form>

              {{-- ปุ่มลบทั้งรายการ (เดิม) --}}
              <form action="{{ route('orders.detail.destroy', $detail->id) }}" method="POST"
                    onsubmit="return confirm('ลบรายการนี้ทั้งหมดใช่ไหม?')">
                @csrf @method('DELETE')
                <button class="text-red-600 hover:text-red-700 px-3 py-1 rounded-lg border border-red-200">
                  ลบรายการ
                </button>
              </form>
            </div>
          </li>
        @endforeach
      </ul>

    </div>
  @empty
    <div class="rounded-2xl border bg-white p-6 text-gray-500">ยังไม่มีคำสั่งซื้อ</div>
  @endforelse

  {{-- ✅ ตัด "เก็บเงินปลายทาง" ออก --}}
  <a href="{{ route('orders.checkout.page') }}" 
   class="rounded-xl border px-4 py-2 inline-block mb-3">
   ยืนยันสั่งซื้อ
  </a>

  <div class="mt-6">{{ $orders->links() }}</div>
  {{-- ปุ่มไปหน้า history --}}
  <div class="mt-6">
    <a href="{{ route('orders.history') }}"
       class="rounded-xl bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
       ดูประวัติคำสั่งซื้อ
    </a>
  </div>
</div>
</x-app-layout>
