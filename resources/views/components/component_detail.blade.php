@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/public_show_blog.css') }}">
@endsection

@section('content')
<main class="blog-detail-container">

  {{-- 1) Botón de volver --}}
  <div class="back-to-list">
 <a href="{{ url('/') }}" class="btn-back">&larr; Volver al inicio</a>  </div>

  {{-- 2) Imagen principal --}}
  <div class="blog-detail-image-wrapper">
    <img 
      src="{{ asset($component->rutaImagenComponente) }}" 
      alt="Imagen de {{ $component->titleComponente }}" 
      class="blog-detail-image"
    >
  </div>

  {{-- 3) Título --}}
  <h1 class="blog-detail-title">{{ $component->titleComponente }}</h1>

  {{-- 4) Descripción breve --}}
  @if($component->description)
    <p class="blog-detail-meta">{{ $component->description }}</p>
  @endif

  {{-- 5) Contenido HTML --}}
  <article class="blog-detail-content markdown-body">
    {!! $component->contentComponente !!}
  </article>

  {{-- 6) Etiquetas relacionadas --}}
  @if(isset($component->tags) && $component->tags->isNotEmpty())
    <div class="blog-components">
      <h2>Etiquetas</h2>
      @foreach($component->tags as $tag)
        <span class="blog-component-badge">{{ $tag->name }}</span>
      @endforeach
    </div>
  @endif

</main>
@endsection
