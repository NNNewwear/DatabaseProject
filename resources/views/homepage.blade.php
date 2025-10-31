<x-app-layout>
  {{-- CATEGORY SECTION --}}
    <div class="py-6 mt-6">
        <div class="mx-auto max-w-6xl px-4 flex gap-8 overflow-x-auto">
        @foreach($categories as $c)
            <a href="{{ route('fe.products.index', ['category_id' => $c->category_id]) }}"
            class="flex flex-col items-center min-w-[90px]">
            <img
                src="{{ asset('storage/' . $c->image_url) }}"
                alt="{{ $c->name }}"
                class="w-50 h-50 rounded-full object-cover border-2 border-blue-200 shadow"
            />
            <span class="mt-2 text-gray-800 font-semibold">{{ $c->name }}</span>
            </a>
        @endforeach
        </div>
    </div>

  {{-- BIG BANNER --}}
    <div class="mx-auto max-w-6xl px-4 mt-6">
        <div class="relative overflow-hidden rounded-2xl">
            <img
            src="{{ $bannerUrl ?? asset('storage/homepage/banner.png') }}"
            alt="Big banner"
            class="w-full h-[420px] md:h-[480px] object-cover"
            />
        </div>

    {{-- BUTTON BELOW BANNER --}}
        <div class="flex justify-center mt-6">
            <a
            href="{{ route('fe.products.index') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition shadow"
            >
            ดูสินค้าทั้งหมด
            </a>
        </div>
    </div>
</x-app-layout>