<x-app-layout>
  <div class="mx-auto max-w-6xl px-4 mt-6">

    {{-- Layout: left sidebar + right product grid --}}
    <div class="flex gap-6">

      {{-- Sidebar --}}
      <aside class="md:w-60 w-60 md:sticky md:top-20">
        <div class="bg-white border rounded-2xl overflow-hidden">
          <div class="px-4 py-3 font-semibold">หมวดหมู่</div>

          {{-- Mobile: horizontal scroll / Desktop: vertical list --}}
          <nav class="flex flex-col border-t">
            <a href="{{ route('fe.products.index') }}"
               class="px-4 py-3 md:block whitespace-nowrap
                 {{ request()->missing('category_id') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
              ทั้งหมด
            </a>

            @foreach($categories as $c)
              <a href="{{ route('fe.products.index', ['category_id' => $c->category_id]) }}"
                 class="px-4 py-3 md:block whitespace-nowrap
                   {{ (string)request('category_id') === (string)$c->category_id
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-50' }}">
                {{ $c->name }}
              </a>
            @endforeach
          </nav>
        </div>
      </aside>

      {{-- Products --}}
      <main class="flex-1">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          @foreach ($products as $p)
            <a href="{{ route('fe.products.show', $p->product_id) }}"
               class="group block rounded-2xl border bg-white overflow-hidden hover:shadow transition flex flex-col">
              <div class="w-full h-60 bg-white flex items-center justify-center overflow-hidden {{ $p->image_url ? '' : 'bg-[#dfeefb]' }}">
                @if($p->image_url)
                  <img src="{{ asset('storage/'.$p->image_url) }}"
                       class="w-full h-full object-cover" alt="{{ $p->name }}">
                @endif
              </div>

              <div class="flex flex-col grow p-3">
                <div class="min-h-[3.5rem]">
                  <div class="font-semibold group-hover:underline line-clamp-1">{{ $p->name }}</div>
                  <div class="text-sm text-gray-500">{{ $p->category->name ?? '-' }}</div>
                </div>
                <div class="mt-auto font-semibold">฿{{ number_format($p->price, 2) }}</div>
              </div>
            </a>
          @endforeach
        </div>

        {{-- Keep current filters in pagination links --}}
        <div class="mt-6">{{ $products->appends(request()->query())->links() }}</div>
      </main>

    </div>
  </div>
</x-app-layout>
