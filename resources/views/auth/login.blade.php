{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
  @php
    $bgPage = 'bg-[#E6F4FF]';
  @endphp

  <div class="{{ $bgPage }} min-h-screen">
 

    <div class="mx-auto w-full max-w-6xl px-6 py-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

        {{-- โลโก้/ภาพด้านซ้าย --}}
        <div class="w-full flex justify-center">
          <div class="w-[600px] h-[500px] flex items-center justify-center">
            <img src="{{ asset('images/login_logo.png') }}"
                 alt="Yott"
                 class="max-w-[80%] max-h-[80%] object-contain">
          </div>
        </div>

        {{-- ฟอร์มด้านขวา --}}
        <div class="w-full">
          {{-- error --}}
          @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
              <input
                id="login"
                name="login"
                type="text"
                placeholder="Username or Email"
                value="{{ old('login') }}"
                required
                autofocus
                class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200"
              />
            </div>

            <div>
              <input
                id="password"
                name="password"
                type="password"
                placeholder="Password"
                required
                class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200"
              />
            </div>

            <div class="flex items-center justify-start gap-3">
              <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded">
                <span class="text-sm text-gray-700">Remember me</span>
              </label>
            </div>

            <div class="flex items-center gap-3">
              <button
                type="submit"
                class="rounded-xl bg-[#14539A] hover:bg-[#0f417a] px-6 py-2 text-white font-semibold">
                LOGIN
              </button>

              <a href="{{ route('register') }}"
                 class="rounded-xl bg-[#34C759] hover:bg-[#2aa94a] px-6 py-2 text-white font-semibold">
                Register
              </a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</x-guest-layout>
