{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
  <div class="mx-auto w-full max-w-6xl px-6">

    <h1 class="text-2xl md:text-3xl font-semibold mb-6">Create an Account</h1>

    {{-- Errors --}}
    @if ($errors->any())
      <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}"
          class="rounded-2xl bg-[#e6f3ff] p-6 md:p-8 shadow-sm">
      @csrf

      {{-- แถว 1: First / Last --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="text" name="firstname" placeholder="Firstname"
               value="{{ old('firstname') }}"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200" required>

        <input type="text" name="lastname" placeholder="Lastname"
               value="{{ old('lastname') }}"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200" required>
      </div>

      {{-- แถว 2: Username / Phone --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <input type="text" name="username" placeholder="Username"
               value="{{ old('username') }}"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200" required>

        <input type="text" name="phone" placeholder="Phone"
               value="{{ old('phone') }}"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200">
      </div>

      {{-- แถว 3: Email / (ว่างไว้ให้สมดุล) --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <input type="email" name="email" placeholder="Email"
               value="{{ old('email') }}"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200" required>
        <div></div>
      </div>

      {{-- แถว 4: Password / Confirm --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <input type="password" name="password" placeholder="Password"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200" required>

        <input type="password" name="password_confirmation" placeholder="Confirmpassword"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200" required>
      </div>

      {{-- แถว 5: Address (เต็มแถว) --}}
      <div class="mt-4">
        <input type="text" name="address" placeholder="Address"
               value="{{ old('address') }}"
               class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200">
      </div>

      <div class="mt-6 flex justify-end">
        <button type="submit"
                class="rounded-xl bg-[#34C759] hover:bg-[#2aa94a] px-6 py-2 text-white font-semibold">
          Create an account
        </button>
      </div>
    </form>
  </div>
</x-guest-layout>
