{{-- resources/views/blogs/public_show.blade.php --}}
@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/public_show_blog.css') }}">
@endsection

@section('content')
<main class="blog-detail-container">
  {{-- Imagen principal --}}
  <div class="blog-detail-image-wrapper">
    <img 
      src="{{ asset($blog->displayImage) }}" 
      alt="Imagen de {{ $blog->title }}" 
      class="blog-detail-image"
    >
  </div>

  {{-- Título y meta --}}
  <h1 class="blog-detail-title">{{ $blog->title }}</h1>
  <p class="blog-detail-meta">
    Por {{ $blog->author->name }} | {{ $blog->created_at->format('d/m/Y') }}
  </p>

  {{-- Contenido ya convertido --}}
  <article class="blog-detail-content">
    {!! $blog->content !!}
  </article>
</main>
@endsection
