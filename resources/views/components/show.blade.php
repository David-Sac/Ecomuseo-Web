@php
  use League\CommonMark\CommonMarkConverter;
  $converter = new CommonMarkConverter(['allow_unsafe_links' => false]);
  $html = $converter->convertToHtml($component->contentComponente);
@endphp

@extends('layouts.app_new')

@section('content')
<div class="row justify-content-center" style="margin-top:80px;">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="float-start">Detalle del Componente</div>
        <div class="float-end">
          <a href="{{ route('components.index') }}" class="btn btn-light btn-sm">&larr; Back</a>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end"><strong>Título:</strong></label>
          <div class="col-md-6">{{ $component->titleComponente }}</div>
        </div>
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end"><strong>Descripción:</strong></label>
          <div class="col-md-6">{{ $component->description }}</div>
        </div>
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end"><strong>Contenido:</strong></label>
          <div class="col-md-6">
            <div class="content-box">{!! $html !!}</div>
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end"><strong>Imagen:</strong></label>
          <div class="col-md-6">
            @if($component->rutaImagenComponente)
              <img
                src="{{ asset($component->rutaImagenComponente) }}"
                class="img-fluid"
                alt="Imagen del componente"
              >
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
