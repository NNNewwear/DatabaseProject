<x-app-layout>
<div class="mx-auto max-w-6xl px-4">
  <h1 class="text-xl font-semibold mb-4">คำสั่งซื้อของฉัน</h1>

  @forelse($orders as $o)
    <div class="mb-3 rounded-2xl border bg-white p-4 flex items-center justify-between">
      <div>
        <div class="font-semibold">Order #{{ $o->order_id }}</div>
        <div class="text-sm text-gray-500">
          {{ \Illuminate\Support\Carbon::parse($o->order_date)->format('d M Y') }} • {{ strtoupper($o->status) }}
        </div>
      </div>
      <div class="font-semibold">฿{{ number_format($o->total_price,2) }}</div>
    </div>
  @empty
    <div class="rounded-2xl border bg-white p-6 text-gray-500">ยังไม่มีคำสั่งซื้อ</div>
  @endforelse

  <div class="mt-6">{{ $orders->links() }}</div>
</div>
</x-app-layout>
