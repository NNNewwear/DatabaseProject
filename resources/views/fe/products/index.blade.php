<x-app-layout>
<div class="mx-auto max-w-6xl px-4">
  <!-- <h1 class="text-2xl font-semibold mb-4">Products</h1> -->

  <!-- <form method="GET" class="mb-6 flex flex-col md:flex-row gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="ค้นหาสินค้า..."
           class="w-full md:w-72 rounded-xl border px-3 py-2">
    <select name="category_id" class="w-full md:w-56 rounded-xl border px-3 py-2">
      <option value="">ทุกหมวดหมู่</option>
      @foreach($categories as $c)
        <option value="{{ $c->category_id }}" @selected(request('category_id')==$c->category_id)>{{ $c->name }}</option>
      @endforeach
    </select>
    <button class="rounded-xl px-4 py-2 bg-gray-900 text-white">ค้นหา</button>
  </form> -->

  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($products as $p)
      <a href="{{ route('fe.products.show',$p->product_id) }}"
         class="group block rounded-2xl border bg-white hover:shadow transition">
        <div class="aspect-video w-full overflow-hidden rounded-t-2xl {{ $p->image_url ? '' : 'bg-[#dfeefb]' }}">
          @if($p->image_url)
            <img src="{{ $p->image_url }}" class="w-full h-full object-cover" alt="">
          @endif
        </div>
        <div class="p-3">
          <div class="font-semibold group-hover:underline line-clamp-1">{{ $p->name }}</div>
          <div class="text-sm text-gray-500">{{ $p->category->name ?? '-' }}</div>
          <div class="mt-1 font-semibold">฿{{ number_format($p->price,2) }}</div>
        </div>
      </a>
    @endforeach
  </div>

  <div class="mt-6">{{ $products->links() }}</div>
</div>
</x-app-layout>
