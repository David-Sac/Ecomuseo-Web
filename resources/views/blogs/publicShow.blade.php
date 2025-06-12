{{-- resources/views/blogs/publicShow.blade.php --}}
@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/public_show_blog.css') }}">
@endsection

@section('content')
  <main class="blog-content-container">
    <div class="back-to-list">
      <a href="{{ route('blogs.publicIndex') }}" class="btn-back">&larr; Volver a Blogs</a>
    </div>

    <h1 class="blog-title">{{ $blog->title }}</h1>
    <div class="blog-meta">
      Autor: {{ $blog->author->name }} | Fecha: {{ $blog->created_at->toFormattedDateString() }}
    </div>

    {{-- Aquí aprovechamos que $blog->content ya es HTML --}}
    <div class="blog-content markdown-body">
      {!! $blog->content !!}
    </div>

    @if($blog->components->isNotEmpty())
      <div class="blog-components">
        <h3>Componentes relacionados:</h3>
        @foreach($blog->components as $c)
          <span class="blog-component-badge">{{ $c->titleComponente }}</span>
        @endforeach
      </div>
    @endif
  </main>
@endsection
