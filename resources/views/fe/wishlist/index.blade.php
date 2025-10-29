<x-app-layout>
<div class="mx-auto max-w-6xl px-4">
  <h1 class="text-xl font-semibold mb-4">Wishlist</h1>

  @if($items->isEmpty())
    <div class="rounded-2xl border bg-white p-6 text-gray-500">ยังไม่มีรายการ</div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($items as $w)
        <div class="rounded-2xl border bg-white p-3 flex gap-3">
          <div class="w-32 h-24 rounded-xl overflow-hidden {{ $w->product->image_url ? '' : 'bg-[#dfeefb]' }}">
            @if($w->product->image_url)
              <img src="{{ $w->product->image_url }}" class="w-full h-full object-cover">
            @endif
          </div>
          <div class="flex-1">
            <a href="{{ route('fe.products.show',$w->product_id) }}" class="font-semibold hover:underline">
              {{ $w->product->name }}
            </a>
            <div class="text-sm text-gray-500">{{ $w->product->category->name ?? '-' }}</div>
            <div class="mt-1 font-semibold">฿{{ number_format($w->product->price,2) }}</div>

            <form method="POST" action="{{ route('wishlist.destroy',$w->product_id) }}" class="mt-2">
              @csrf @method('DELETE')
              <button class="rounded-xl border px-3 py-1.5 text-sm">ลบ</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
</x-app-layout>
