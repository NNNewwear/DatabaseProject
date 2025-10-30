<x-app-layout>
  {{-- CATEGORY SECTION --}}
    <div class="bg-blue-100 py-6 mt-6">
        <div class="mx-auto max-w-6xl px-4 flex gap-8 overflow-x-auto">
        @foreach($categories as $c)
            <a href="{{ route('fe.products.index', ['category_id' => $c->category_id]) }}"
            class="flex flex-col items-center min-w-[90px] hover:scale-105 transition">
            <img
                src="{{ $c->image_url ?? asset('storage/homepage/bicycle.png') }}"
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
            <a href="{{ route('fe.products.index') }}">
            <img
            src="{{ $bannerUrl ?? asset('storage/homepage/banner.png') }}"
            alt="Big banner"
            class="w-full h-[420px] md:h-[520px] object-cover"
            />
            </a>           
        </div>
    </div>
</x-app-layout>