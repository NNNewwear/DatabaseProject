<x-app-layout>
<div class="mx-auto max-w-6xl px-4">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="{{ $product->image_url ? '' : 'bg-[#dfeefb]' }} rounded-2xl overflow-hidden border">
      @if($product->image_url)
        <img src="{{ asset('storage/' . $product->image_url) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
      @endif
    </div>
    <div>
      <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
      <p class="text-gray-500 mb-4">{{ $product->category->name ?? '-' }}</p>
      <p class="mb-6 leading-relaxed">{{ $product->description }}</p>
      <div class="text-2xl font-semibold mb-4">฿{{ number_format($product->price,2) }}</div>

      @auth
        <div class="flex gap-3">
          @if($inWishlist)
            <form method="POST" action="{{ route('wishlist.destroy', $product->product_id) }}">
              @csrf @method('DELETE')
              <button class="rounded-xl border px-4 py-2">♥ Saved</button>
            </form>
          @else
            <form method="POST" action="{{ route('wishlist.store') }}">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->product_id }}">
              <button class="rounded-xl border px-4 py-2">♡ Wishlist</button>
            </form>
          @endif
          <form action="{{ route('orders.add', $product) }}" method="POST">
            @csrf
            <input type="number" name="qty" value="1" min="1" class="w-16 border rounded px-2 py-1">
            <button class="rounded-xl border px-4 py-2">เพิ่มลงตะกร้า</button>
          </form>
        </div>
        
      @else
        <a href="{{ route('login') }}" class="rounded-xl border px-4 py-2">Login เพื่อบันทึก</a>
      @endauth
    </div>
  </div>
</div>
</x-app-layout>
