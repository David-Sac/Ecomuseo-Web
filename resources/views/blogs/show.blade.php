@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="float-start">{{ $blog->title }}</div>
    <div class="float-end">
      <a href="{{ route('blogs.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
    </div>
  </div>
  <div class="card-body">
    {{-- Mostrar la imagen a tamaño más grande --}}
    @if($blog->image_path)
    <div class="mb-4 text-center">
      <img src="{{ asset('storage/'.$blog->image_path) }}"
           alt="Imagen de {{ $blog->title }}"
           style="max-width:100%; height:auto; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.1)">
    </div>
    @endif

    <p><strong>Autor:</strong> {{ $blog->author->name }}</p>
    <p><strong>Fecha:</strong> {{ $blog->created_at->format('Y-m-d') }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($blog->status) }}</p>

    <hr>

    {{-- Puedes continuar mostrando el contenido parseado --}}
    <div class="prose">
      {!! \Illuminate\Support\Str::markdown($blog->content) !!}
    </div>

    <hr>

    <p><strong>Componentes:</strong>
      @foreach($blog->components as $c)
        <span class="badge bg-info">{{ $c->titleComponente }}</span>
      @endforeach
    </p>
  </div>
</div>
@endsection