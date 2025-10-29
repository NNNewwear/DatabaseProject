<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="font-sans text-gray-900 antialiased">
    {{-- ทำให้เต็มหน้าจอ --}}
    <div class="min-h-screen flex flex-col bg-[#E6F4FF]">
      {{-- ถ้ามี navbar --}}
      @include('layouts.navigation')

      {{-- เนื้อหาหลัก --}}
      <main class="flex-1 w-full px-8 md:px-16 py-10">
        {{ $slot }}
      </main>
    </div>
  </body>
</html>
