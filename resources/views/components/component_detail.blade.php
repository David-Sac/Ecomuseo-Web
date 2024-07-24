<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/component_detail.css') }}">
    <script src="{{ asset('js/welcome.js') }}"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_vectorizado.svg') }}">
    <title>Ecomuseo LLAQTA AMARU -YOYEN KUWA</title>
</head>
<body>
    <header>
        @include('partials.header_new')
    </header>

    <main>
        <article class="component-detail">

            <section class="component-body">
                <img src="{{ asset($component->rutaImagenComponente) }}" alt="Imagen de {{ $component->titleComponente }}" class="component-image">
                <h1 class="component-title">{{ $component->titleComponente }}</h1>
                <p class="component-description">{{ $component->description }}</p>
                <div class="component-content">
                    {!! $component->contentComponente !!}
                </div>
            </section>
        </article>
    </main>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html>
