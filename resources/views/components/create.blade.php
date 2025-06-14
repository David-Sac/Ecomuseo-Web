@extends('layouts.app_new')

@section('styles')
  <!-- Font Awesome (EasyMDE) -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- EasyMDE -->
  <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
  <!-- Tus estilos -->
  <link rel="stylesheet" href="{{ asset('css/intranet/components-create.css') }}">
@endsection

@section('content')
<div class="row justify-content-center" style="margin-top:80px;">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="float-start">Añadir Nuevo Componente</div>
        <div class="float-end">
          <a href="{{ route('components.index') }}" class="btn btn-light btn-sm">&larr; Back</a>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('components.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <!-- Título -->
          <div class="mb-3 row">
            <label for="titleComponente" class="col-md-4 col-form-label text-md-end">Título</label>
            <div class="col-md-6">
              <input
                type="text"
                id="titleComponente"
                name="titleComponente"
                class="form-control @error('titleComponente') is-invalid @enderror"
                value="{{ old('titleComponente') }}"
              >
              @error('titleComponente')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Descripción -->
          <div class="mb-3 row">
            <label for="description" class="col-md-4 col-form-label text-md-end">Descripción</label>
            <div class="col-md-6">
              <textarea
                id="description"
                name="description"
                class="form-control @error('description') is-invalid @enderror"
                rows="3"
              >{{ old('description') }}</textarea>
              @error('description')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Contenido (Markdown) -->
          <div class="mb-3 row">
            <label for="contentComponente" class="col-md-4 col-form-label text-md-end">Contenido</label>
            <div class="col-md-6">
              <textarea
                id="contentComponente"
                name="contentComponente"
                class="form-control @error('contentComponente') is-invalid @enderror"
                rows="6"
              >{{ old('contentComponente') }}</textarea>
              @error('contentComponente')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Imagen -->
          <div class="mb-3 row">
            <label for="rutaImagenComponente" class="col-md-4 col-form-label text-md-end">Imagen</label>
            <div class="col-md-6">
              <input
                type="file"
                id="rutaImagenComponente"
                name="rutaImagenComponente"
                accept="image/*"
                class="form-control @error('rutaImagenComponente') is-invalid @enderror"
              >
              @error('rutaImagenComponente')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Botón de envío -->
          <div class="mb-3 row">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle"></i> Añadir Componente
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      new EasyMDE({
        element: document.getElementById('contentComponente'),
        autoDownloadFontAwesome: true,
        minHeight: '300px'
      });
    });
  </script>
@endsection
