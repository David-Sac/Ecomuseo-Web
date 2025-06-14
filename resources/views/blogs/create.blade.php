@extends('layouts.app_new')

{{-- 1) Sólo CSS en styles --}}
@section('styles')
  <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs-create.css') }}">
@endsection

@section('content')
<div class="row justify-content-center" style="margin-top: 80px;">
  <div class="col-12 col-lg-10">
    <div class="card">
      <div class="card-header">
        <div class="float-start">Añadir Nuevo Blog</div>
        <div class="float-end">
          <a href="{{ route('blogs.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('blogs.store') }}" method="post">
          @csrf

          <div class="mb-3 row">
            <label for="title" class="col-md-2 col-form-label text-md-end">Título</label>
            <div class="col-md-10">
              <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                class="form-control @error('title') is-invalid @enderror"
              >
              @error('title')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mb-3 row">
            <label for="content" class="col-md-2 col-form-label text-md-end">Contenido</label>
            <div class="col-md-10">
              <textarea
                id="content"
                name="content"
                class="form-control @error('content') is-invalid @enderror"
              >{{ old('content') }}</textarea>
              @error('content')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-md-2 col-form-label text-md-end">Componentes</label>
            <div class="col-md-10">
              @foreach ($components as $component)
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="component{{ $component->id }}"
                    name="components[]"
                    value="{{ $component->id }}"
                  >
                  <label class="form-check-label" for="component{{ $component->id }}">
                    {{ $component->titleComponente }}
                  </label>
                </div>
              @endforeach
              @error('components')
                <div><span class="text-danger">{{ $message }}</span></div>
              @enderror
            </div>
          </div>

          <div class="mb-3 row">
            <div class="col-md-10 offset-md-2">
              <button type="submit" class="btn btn-primary px-4">Añadir Blog</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- 2) Sólo JS en scripts --}}
@section('scripts')
  <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new EasyMDE({
        element: document.getElementById('content'),
        autoDownloadFontAwesome: true
      });
    });
  </script>
@endsection
