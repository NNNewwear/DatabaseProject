{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="bg-[#14539A] text-white w-full">
  <!-- h = ~10% ของความสูงจอ | คุมกรอบไม่ให้เล็ก/ใหญ่เกินไป -->
  <div class="w-full h-[10vh] min-h-[72px] max-h-[120px] px-[4vw] flex items-center justify-between">

    {{-- ซ้าย: โลโก้ (สเกลตามความสูงจอ) --}}
    <a href="{{ route('fe.products') }}" class="flex items-center gap-2">
      <img src="{{ asset('images/logo.png') }}" alt="Logo"
           class="h-[6vh] max-h-[48px] w-auto" />
    </a>

    {{-- ขวา: แถบค้นหา + ไอคอน (สเกลตามจอกว้าง/สูง) --}}
    <div class="flex items-center gap-[2vw]">

      {{-- ช่องค้นหา --}}
      <form method="GET" action="{{ route('fe.products') }}" class="flex items-center">
        <div class="relative">
          <input type="text" name="search" placeholder="Search..."
                 value="{{ request('search') }}"
                 class="pl-9 pr-3 py-[1.2vh] w-[34vw] min-w-[180px] max-w-[560px]
                        rounded-full text-black focus:ring-2 focus:ring-blue-200 outline-none" />
          {{-- ไอคอนแว่น --}}
          <svg xmlns="http://www.w3.org/2000/svg"
               class="absolute left-3 top-1/2 -translate-y-1/2 h-[2.2vh] w-[2.2vh] min-h-[14px] min-w-[14px] text-gray-500"
               fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m21 21-4.35-4.35M17.65 10.5A7.15 7.15 0 1 1 3.35 10.5a7.15 7.15 0 0 1 14.3 0Z" />
          </svg>
        </div>
      </form>

      {{-- Wishlist --}}
      <a href="{{ route('fe.wishlist') }}" title="Wishlist" class="hover:opacity-80">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.8" stroke="white"
             class="w-[3vh] h-[3vh] min-w-[20px] min-h-[20px]">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733C11.285 4.876 9.623 3.75 7.688 3.75 5.099 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
        </svg>
      </a>

      {{-- Cart --}}
      <a href="{{ route('fe.orders') }}" title="My Orders" class="hover:opacity-80">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.8" stroke="white"
             class="w-[3vh] h-[3vh] min-w-[20px] min-h-[20px]">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 2.25h1.386a.75.75 0 0 1 .728.568L4.7 6.75m0 0h15.75l-1.5 8.25H6.25M4.7 6.75l-.732 4.016M6.25 15l-.675 3.825a.75.75 0 0 0 .742.9h12.666M6.25 15h13.5m-9 3.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm9 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
      </a>

      {{-- Profile --}}
      @auth
        <a href="{{ route('profile.show') }}" title="Profile" class="hover:opacity-80">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke-width="1.8" stroke="white"
               class="w-[3vh] h-[3vh] min-w-[20px] min-h-[20px]">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
          </svg>
        </a>
      @else
        <a href="{{ route('login') }}" title="Login" class="hover:opacity-80">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke-width="1.8" stroke="white"
               class="w-[3vh] h-[3vh] min-w-[20px] min-h-[20px]">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
          </svg>
        </a>
      @endauth
    </div>
  </div>
</nav>
