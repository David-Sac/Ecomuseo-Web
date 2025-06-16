@php
  use League\CommonMark\CommonMarkConverter;
  $converter = new CommonMarkConverter(['allow_unsafe_links' => false]);
@endphp

@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/components-show.css') }}">
@endsection

@section('content')
<div class="row justify-content-center my-5">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <span>Detalle del Componente</span>
        <a href="{{ route('components.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
      </div>
      <div class="card-body">
        <!-- Título -->
        <div class="mb-3 row">
          <label class="col-md-2 col-form-label text-md-end"><strong>Título:</strong></label>
          <div class="col-md-10">
            {{ $component->titleComponente }}
          </div>
        </div>

        <!-- Descripción -->
        <div class="mb-3 row">
          <label class="col-md-2 col-form-label text-md-end"><strong>Descripción:</strong></label>
          <div class="col-md-10">
            {{ $component->description }}
          </div>
        </div>

        <!-- Contenido Markdown -->
        <div class="mb-3 row">
          <label class="col-md-2 col-form-label text-md-end"><strong>Contenido:</strong></label>
          <div class="col-md-10 markdown-body">
            {!! $converter->convertToHtml($component->contentComponente) !!}
          </div>
        </div>

        <!-- Imagen -->
        @if($component->rutaImagenComponente)
        <div class="mb-3 row">
          <label class="col-md-2 col-form-label text-md-end"><strong>Imagen:</strong></label>
          <div class="col-md-10">
            <img 
              src="{{ asset($component->rutaImagenComponente) }}" 
              class="img-fluid rounded shadow-sm" 
              alt="Imagen del componente"
            >
          </div>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>
@endsection
