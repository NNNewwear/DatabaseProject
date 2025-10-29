{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
  @php
    $u = $user ?? auth()->user();
  @endphp

  <div class="py-8">
    <div class="max-w-6xl mx-auto px-4">
      <div class="rounded-2xl bg-[#e6f3ff] p-6 md:p-8 ">

        @if ($errors->any())
          <div class="mb-4 rounded-xl border bg-red-50 text-red-800 px-4 py-3">
            {{ $errors->first() }}
          </div>
        @endif

        <h1 class="text-3xl md:text-4xl font-semibold mb-8 text-center md:text-left">
         Edit an Account
        </h1>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
          @csrf
          @method('PATCH')

          {{-- แถวบน: Firstname / Lastname --}}
          <div class="grid md:grid-cols-2 gap-4">
            <input name="firstname" placeholder="Firstname"
                   class="rounded-xl border px-4 py-2.5"
                   value="{{ old('firstname',$u->firstname) }}">
            <input name="lastname" placeholder="Lastname"
                   class="rounded-xl border px-4 py-2.5"
                   value="{{ old('lastname',$u->lastname) }}">
          </div>

          {{-- แถวสอง: Username / Phone --}}
          <div class="grid md:grid-cols-2 gap-4">
            <input name="username" placeholder="Username"
                   class="rounded-xl border px-4 py-2.5"
                   value="{{ old('username',$u->username) }}">
            <input name="phone" placeholder="Phone"
                   class="rounded-xl border px-4 py-2.5"
                   value="{{ old('phone',$u->phone) }}">
          </div>

          {{-- แถวสาม: Email --}}
          <div class="grid md:grid-cols-2 gap-4">
            <input type="email" name="email" placeholder="Email"
                   class="rounded-xl border px-4 py-2.5"
                   value="{{ old('email',$u->email) }}">
            <div></div>
          </div>

          {{-- (ทางเลือก) เปลี่ยนรหัสผ่าน: password / confirm --}}
          <div class="grid md:grid-cols-2 gap-4">
            <input type="password" name="password" placeholder="Password (optional)"
                   class="rounded-xl border px-4 py-2.5">
            <input type="password" name="password_confirmation" placeholder="Confirm password"
                   class="rounded-xl border px-4 py-2.5">
          </div>

          {{-- Address --}}
          <input name="address" placeholder="Address"
                 class="w-full rounded-xl border px-4 py-2.5"
                 value="{{ old('address',$u->address) }}">


          <div class="text-right">
            <button class="rounded-xl bg-green-400 hover:bg-green-500 px-6 py-2.5 font-semibold">
              Confirm Change
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</x-app-layout>
