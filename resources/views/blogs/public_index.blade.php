{{-- resources/views/blogs/public_index.blade.php --}}
@extends('layouts.app_new')

@section('styles')
  <!-- aquí añades SOLO el CSS específico de blogs -->
  <link rel="stylesheet" href="{{ asset('css/public_index_blog.css') }}">
@endsection

@section('content')
<main class="blog-previews-container">
  <h1 class="blog-previews-title">Nuestros Blogs</h1>
  <div class="blog-previews">
    @foreach ($blogs as $blog)
      <div class="blog-preview-card">
        <div class="blog-image-wrapper">
          <img
            src="{{ asset($blog->displayImage) }}"
            alt="Imagen de {{ $blog->title }}"
            class="blog-preview-image"
          >
        </div>
        <div class="blog-preview-content">
          <h2 class="blog-preview-title">{{ $blog->title }}</h2>
          <div class="blog-preview-components">
            @foreach ($blog->components as $c)
              <span class="blog-component-badge">{{ $c->titleComponente }}</span>
            @endforeach
          </div>
          <a href="{{ route('blogs.publicShow', $blog) }}" class="blog-preview-link">
            Leer más →
          </a>
        </div>
      </div>
    @endforeach
  </div>
</main>
@endsection
