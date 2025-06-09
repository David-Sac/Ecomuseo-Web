<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blogs | Ecomuseo LLAQTA AMARU – YOYEN KUWA</title>
  <link rel="stylesheet" href="{{ asset('css/public_index_blog.css') }}">
</head>
<body>
  <!-- Header general del sitio -->
  <header>
    @include('partials.header_new')
  </header>

  <!-- Contenedor principal de previsualizaciones de blogs -->
  <main class="blog-previews-container">
    <h1 class="blog-previews-title">Nuestros Blogs</h1>

    <div class="blog-previews">
      @foreach ($blogs as $blog)
        <div class="blog-preview-card">
          <div class="blog-image-wrapper">
            <img
              src="{{ asset($blog->displayImage) }}"
              alt="Imagen representativa del blog: {{ $blog->title }}"
              class="blog-preview-image"
            >
          </div>
          <div class="blog-preview-content">
            <h2 class="blog-preview-title">{{ $blog->title }}</h2>

            {{-- Si quieres un extracto corto, descomenta la línea de abajo:
            <p class="blog-preview-excerpt">{{ Str::limit(strip_tags($blog->content), 150) }}</p> 
            --}}

            <div class="blog-preview-components">
              @foreach ($blog->components as $component)
                <span class="blog-component-badge">{{ $component->titleComponente }}</span>
              @endforeach
            </div>

            <a href="{{ route('blogs.publicShow', $blog->id) }}" class="blog-preview-link">
              Leer más →
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </main>

  <!-- Footer general del sitio -->
  <footer>
    @include('partials.footer')
  </footer>
</body>
</html>
