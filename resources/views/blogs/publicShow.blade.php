@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/public_show_blog.css') }}">
@endsection

@section('content')
<main class="blog-detail-container">
  <div class="back-to-list">
    <a href="{{ route('blogs.publicIndex') }}" class="btn-back">&larr; Volver a Blogs</a>
  </div>
  <div class="blog-detail-image-wrapper">
    <img src="{{ asset($blog->displayImage) }}"
         alt="Imagen de {{ $blog->title }}"
         class="blog-detail-image">
  </div>
  <h1 class="blog-detail-title">{{ $blog->title }}</h1>
  <p class="blog-detail-meta">…</p>
  <article class="blog-detail-content">
    {!! $blog->content !!}
  </article>

  {{-- 5) Componentes relacionados --}}
  @if($blog->components->isNotEmpty())
    <div class="blog-components">
      <h2>Componentes</h2>
      @foreach ($blog->components as $c)
        <span class="blog-component-badge">{{ $c->titleComponente }}</span>
      @endforeach
    </div>
  @endif

</main>
@endsection
