<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name','Ecomuseo') }}</title>

  @vite(['resources/sass/app.scss','resources/js/app.js'])
  @yield('styles')

  <link rel="stylesheet" href="{{ asset('css/intranet/app_new.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header_new.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>
  <div id="app">
    @include('partials.header_new')
    <main class="intranet-main">
      @yield('content')
    </main>
    @include('partials.footer')
  </div>
  @yield('scripts')
</body>
</html>
