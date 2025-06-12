<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','Ecomuseo') }}</title>

  <!-- estilos globales, Bootstrap, Vite, etc -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  @yield('styles')
  <link rel="stylesheet" href="{{ asset('css/header_new.css') }}">

</head>
<body>
  <div id="app">
    {{-- Aquí insertamos nuestro header “nuevo” --}}
    @include('partials.header_new')

    {{-- espacio principal para cada vista --}}
    <main class="py-4">
      @yield('content')
    </main>

    @include('partials.footer')
  </div>

  @yield('scripts')
</body>
</html>
