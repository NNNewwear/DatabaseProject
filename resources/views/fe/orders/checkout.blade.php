<x-app-layout>
<div class="mx-auto max-w-6xl px-4">
  <h1 class="text-2xl font-semibold text-center my-6">Checkout</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    {{-- ซ้าย: ฟอร์มข้อมูล --}}
    <div>
      @php($u = auth()->user())

      {{-- แสดง validation errors --}}
      @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('orders.checkout') }}" class="space-y-3">
        @csrf

        <h2 class="text-lg font-semibold mb-3">ข้อมูลการจัดส่ง</h2>

        <input name="email" type="email" class="w-full border rounded px-3 py-2"
               placeholder="Email" value="{{ old('email', $u?->email) }}" required>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <input name="first_name" class="border rounded px-3 py-2"
                 placeholder="First Name" value="{{ old('first_name', $u?->firstname) }}" required>
          <input name="last_name" class="border rounded px-3 py-2"
                 placeholder="Last Name" value="{{ old('last_name', $u?->lastname) }}">
        </div>

        <input name="address" class="w-full border rounded px-3 py-2"
               placeholder="Address" value="{{ old('address', $u?->address) }}" required>

        <input name="phone" class="w-full border rounded px-3 py-2"
               placeholder="Phone Number" value="{{ old('phone', $u?->phone) }}" required>

        <h2 class="text-lg font-semibold mt-6">การชำระเงิน</h2>

        @if(!empty($cards) && $cards->count())
          <label class="block mb-1">เลือกบัตรของคุณ</label>
          <select name="card_id" class="border rounded px-3 py-2 w-full" required>
            @foreach($cards as $c)
              <option value="{{ $c->card_id }}" {{ old('card_id') == $c->card_id ? 'selected' : '' }}>
                **** **** **** {{ substr($c->card_no, -4) }}
                (หมดอายุ {{ \Illuminate\Support\Carbon::parse($c->expire_date)->format('m/Y') }})
              </option>
            @endforeach
          </select>
        @else
          <div class="p-3 bg-yellow-50 border rounded">
            ยังไม่มีบัตรในระบบของคุณ — โปรดเพิ่มบัตรก่อนทำการชำระเงิน
          </div>
        @endif

        <button type="submit" class="rounded-xl border px-4 py-2">ยืนยันสั่งซื้อ</button>
      </form>
    </div>

    {{-- ขวา: สรุปตะกร้า --}}
    <div>
      <h2 class="text-lg font-semibold mb-3">สรุปรายการสินค้า</h2>
      <div class="rounded-2xl border bg-white p-4 space-y-2">
        @foreach ($order->orderDetails as $d)
          <div class="flex justify-between">
            <div>{{ $d->product->name ?? '-' }} × {{ $d->total_amount }}</div>
            <div>฿{{ number_format($d->total_price, 2) }}</div>
          </div>
        @endforeach
        <hr>
        <div class="flex justify-between font-semibold">
          <div>ยอดรวม</div>
          <div>฿{{ number_format($order->total_price, 2) }}</div>
        </div>
      </div>
    </div>

  </div>
</div>
</x-app-layout>
