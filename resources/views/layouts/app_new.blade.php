<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name','Ecomuseo') }}</title>

  {{-- Vite / Bootstrap --}}
  @vite(['resources/sass/app.scss','resources/js/app.js'])

  {{-- Aquí caen estilos extra de cada vista --}}
  @yield('styles')

  {{-- Solo la intranet carga estos CSS --}}
  <link rel="stylesheet" href="{{ asset('css/intranet/app_new.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header_new.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>
  <div id="app">
    {{-- header fijo --}}
    @include('partials.header_new')

    {{-- contenido --}}
    <main class="intranet-main">
      @yield('content')
    </main>

    {{-- footer --}}
    @include('partials.footer')
  </div>

  @yield('scripts')
</body>
</html>
