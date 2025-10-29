{{-- resources/views/profile/show.blade.php --}}
<x-app-layout>
  @php
    $bgPage  = 'bg-[#E6F4FF]';     // ฟ้าอ่อน
    $panel   = 'bg-[#d7dee8]';     // กล่องเทาอ่อน
    $u       = $user ?? auth()->user();
    $avatar  = $u->profile_image_path ? asset('storage/'.$u->profile_image_path) : null;

    // first + last (ถ้าไม่มีให้เป็น '-')
    $fullName = trim(($u->firstname ?? '').' '.($u->lastname ?? ''));
    if ($fullName === '') $fullName = '-';
  @endphp

  <div class="{{ $bgPage }} min-h-screen py-12">
    <div class="w-full px-6 md:px-12 lg:px-20">

      {{-- ส่วนบน: รูปและชื่อ --}}
      <div class="flex items-end gap-8 md:gap-10 mb-10">
        {{-- รูปโปรไฟล์ --}}
        <a href="{{ route('profile.image') }}"
           class="block relative w-36 h-36 md:w-56 md:h-56 rounded-full ring-4 ring-amber-400 overflow-hidden hover:opacity-95 transition {{ $avatar ? '' : 'bg-[#cce0ff]' }}">
          @if($avatar)
            <img src="{{ $avatar }}" class="w-full h-full object-cover" alt="User Avatar">
          @endif
        </a>

        {{-- username อยู่ขวาล่าง --}}
        <div class="self-end pb-3">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
            {{ $u->username ?? 'User' }}
          </h1>
        </div>
      </div>

      {{-- กล่องข้อมูล --}}
      <div class="{{ $panel }} w-full rounded-3xl p-8 md:p-10">
        <div class="grid grid-cols-[140px_1fr] gap-y-5 text-lg leading-7">
          <div class="text-gray-700 font-medium">Name :</div>
          <div class="pr-4">{{ $fullName }}</div>

          <div class="text-gray-700 font-medium">Email :</div>
          <div class="pr-4">{{ $u->email }}</div>

          <div class="text-gray-700 font-medium">Address :</div>
          <div class="pr-4">{{ $u->address ?? '-' }}</div>

          <div class="text-gray-700 font-medium">Phone :</div>
          <div class="pr-4">{{ $u->phone ?? '-' }}</div>
        </div>

        {{-- ===== Card Management Section ===== --}}
        <div class="mt-8">
          <div class="pr-4 font-medium text-lg mb-2">My cards</div>

          {{-- Add Card Form --}}
          <form method="POST" action="{{ route('cards.store') }}"
                class="mb-6 rounded-2xl border bg-white p-6 grid grid-cols-1 md:grid-cols-3 gap-4 shadow-sm">
            @csrf
            <div class="flex flex-col">
              <label for="card_no" class="text-gray-700 text-sm mb-1">Card Number</label>
              <input name="card_no" id="card_no"
                     class="rounded-xl border px-3 py-3 placeholder-gray-400"
                     placeholder="**** **** ****" required>
            </div>

            <div class="flex flex-col">
              <label for="expire_date" class="text-gray-700 text-sm mb-1">Expiration Date</label>
              <input type="date" id="expire_date" name="expire_date"
                     class="rounded-xl border px-3 py-3" required>
            </div>

            <button class="rounded-xl bg-[#14539A] hover:bg-[#0f417a] text-white px-4 py-3 font-semibold transition">
              Add Card
            </button>
          </form>

          {{-- Card List --}}
          @forelse($cards as $c)
            @php
              $exp = \Illuminate\Support\Carbon::parse($c->expire_date);
              $isExpired = $exp->isPast();
            @endphp
            <div class="mb-3 rounded-2xl border bg-white p-5 flex items-center justify-between shadow-sm">
              <div>
                <div class="font-semibold text-lg">**** **** **** {{ substr($c->card_no, -4) }}</div>
                <div class="text-sm mt-1">
                  <span class="text-gray-600">Expiration: {{ $exp->format('Y-m') }}</span>
                  @if($isExpired)
                    <span class="ml-2 inline-block rounded-full bg-red-100 text-red-700 px-2 py-0.5 text-xs align-middle">Expired</span>
                  @endif
                </div>
              </div>
              <form method="POST" action="{{ route('cards.destroy', $c->card_id) }}">
                @csrf @method('DELETE')
                <button class="rounded-xl px-4 py-2 border border-gray-300 hover:bg-gray-100 transition">
                  Delete
                </button>
              </form>
            </div>
          @empty
            <div class="rounded-2xl border bg-white p-6 text-gray-500 shadow-sm">
              No cards yet.
            </div>
          @endforelse
        </div>

        {{-- ปุ่ม edit + logout + delete account --}}
        <div class="mt-8 flex flex-wrap justify-end gap-4">
          <a href="{{ route('profile.edit') }}"
             class="inline-block rounded-full bg-amber-400 hover:bg-amber-500 px-8 py-3 text-lg font-semibold">
            EDIT
          </a>

          {{-- Logout --}}
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
              class="inline-block rounded-full bg-gray-400 hover:bg-gray-500 px-8 py-3 text-lg font-semibold text-white transition">
              LOG OUT
            </button>
          </form>

          {{-- Delete Account (open modal) --}}
          <button id="openDeleteModal"
                  class="inline-block rounded-full bg-red-500 hover:bg-red-600 px-8 py-3 text-lg font-semibold text-white transition">
            DELETE ACCOUNT
          </button>
        </div>
      </div>

    </div>
  </div>

  {{-- ===== Delete Account Modal ===== --}}
  <div id="deleteModal"
       class="fixed inset-0 z-50 hidden items-center justify-center">
    {{-- backdrop --}}
    <div class="absolute inset-0 bg-black/50"></div>

    {{-- panel --}}
    <div class="relative z-10 w-[92%] max-w-md rounded-2xl bg-white p-6 shadow-xl">
      <h2 class="text-xl font-semibold mb-1">Delete account</h2>
      <p class="text-sm text-gray-600 mb-4">
        This action is permanent and cannot be undone. Please confirm your password to proceed.
      </p>

      <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
        @csrf
        @method('DELETE')

        {{-- ถ้า controller ใช้ validateWithBag('userDeletion', ['password' => ['required', 'current_password']]) --}}
        <label for="delete_password" class="block text-sm text-gray-700 mb-1">Password</label>
        <input type="password" name="password" id="delete_password"
               class="w-full rounded-xl border px-4 py-2.5 mb-5" placeholder="Current password" required>

        <div class="flex justify-end gap-3">
          <button type="button" id="cancelDelete"
                  class="rounded-xl border px-5 py-2.5 hover:bg-gray-50">Cancel</button>
          <button type="submit"
                  class="rounded-xl bg-red-500 hover:bg-red-600 px-5 py-2.5 text-white font-semibold">
            Confirm Delete
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal script --}}
  <script>
    (function () {
      const modal = document.getElementById('deleteModal');
      const openBtn = document.getElementById('openDeleteModal');
      const cancelBtn = document.getElementById('cancelDelete');

      function open() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('delete_password')?.focus(), 50);
      }
      function close() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }

      openBtn?.addEventListener('click', open);
      cancelBtn?.addEventListener('click', close);
      modal?.addEventListener('click', (e) => { if (e.target === modal) close(); });
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    })();
  </script>
</x-app-layout>
