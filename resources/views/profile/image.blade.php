{{-- resources/views/profile/image.blade.php --}}
<x-app-layout>

  @php
    $u = $user ?? auth()->user();
    $avatar = $u->profile_image_path ? asset('storage/'.$u->profile_image_path) : null;
  @endphp

  <div class="bg-[#e6f3ff] min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md bg-[#e6f3ff] text-center">
      {{-- แสดงภาพ preview --}}
      <div id="preview-container" class="mb-6 flex justify-center">
        @if ($avatar)
          <img id="preview" src="{{ $avatar }}" alt="preview" class="w-64 h-64 rounded-xl object-cover ring-4 ring-amber-400">
        @else
          <div id="preview-placeholder" class="w-64 h-64 rounded-xl bg-gray-300 flex items-center justify-center text-gray-500">
            No image
          </div>
        @endif
      </div>

      {{-- ฟอร์มอัปโหลด --}}
      <form method="POST" action="{{ route('profile.image.update') }}" enctype="multipart/form-data" id="uploadForm" class="space-y-4">
        @csrf
        <input type="file" name="profile_image" id="profile_image" accept="image/*"
               class="block w-full text-sm text-gray-700 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none"
               onchange="previewImage(event)">
        <div class="flex justify-center gap-4 pt-6">
          <a href="{{ route('profile.show') }}"
             class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-6 rounded-full">
            Cancel
          </a>
          <button type="submit"
                  class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-full">
            Confirm
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- JS preview ก่อนอัปโหลด --}}
  <script>
    function previewImage(event) {
      const file = event.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        let img = document.getElementById('preview');
        let placeholder = document.getElementById('preview-placeholder');
        if (placeholder) placeholder.remove();
        if (!img) {
          img = document.createElement('img');
          img.id = 'preview';
          img.className = 'w-64 h-64 rounded-xl object-cover ring-4 ring-amber-400';
          document.getElementById('preview-container').appendChild(img);
        }
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  </script>
</x-app-layout>
