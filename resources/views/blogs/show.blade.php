@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/public_show_blog.css') }}">
@endsection

@section('content')
<main class="blog-content-container">

  {{-- Botón Volver arriba --}}
  <div class="back-to-list">
    <a href="{{ route('blogs.index') }}" class="btn-back">← Volver a Blogs</a>
  </div>

  {{-- Título --}}
  <h1 class="blog-title">{{ $blog->title }}</h1>

  {{-- Meta datos --}}
  <div class="blog-meta">
    Autor: {{ $blog->author->name }} | Fecha: {{ $blog->created_at->toFormattedDateString() }}
  </div>

  {{-- Contenido con scroll vertical --}}
  <div class="blog-content markdown-body">
    @markdown
    {{ $blog->content }}
    @endmarkdown
  </div>

  {{-- Componentes relacionados --}}
  @if($blog->components->isNotEmpty())
    <div class="blog-components">
      <h2>Componentes relacionados</h2>
      @foreach($blog->components as $component)
        <span class="blog-component-badge">{{ $component->titleComponente }}</span>
      @endforeach
    </div>
  @endif

</main>
@endsection
