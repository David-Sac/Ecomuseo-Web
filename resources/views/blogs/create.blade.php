@extends('layouts.app_new')

{{-- 1) Solo CSS en styles --}}
@section('styles')
  <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs-create.css') }}">
@endsection

@section('content')
<div class="row justify-content-center" style="margin-top: 80px;">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="float-start">
          Añadir Nuevo Blog
        </div>
        <div class="float-end">
          <a href="{{ route('blogs.index') }}" class="btn btn-light btn-sm">
            &larr; Volver
          </a>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('blogs.store') }}" method="post">
          @csrf

          <div class="mb-3 row">
            <label for="title" class="col-md-4 col-form-label text-md-end">Título</label>
            <div class="col-md-6">
              <input
                type="text"
                class="form-control @error('title') is-invalid @enderror"
                id="title" name="title"
                value="{{ old('title') }}"
              >
              @error('title')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mb-3 row">
            <label for="content" class="col-md-4 col-form-label text-md-end">Contenido</label>
            <div class="col-md-6">
              <textarea
                class="form-control @error('content') is-invalid @enderror"
                id="content" name="content"
                rows="10"
              >{{ old('content') }}</textarea>
              @error('content')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end">Componentes</label>
            <div class="col-md-6">
              @foreach ($components as $component)
                <div class="form-check">
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
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mb-3 row">
            <input
              type="submit"
              class="col-md-3 offset-md-5 btn btn-primary"
              value="Añadir Blog"
            >
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- 2) Solo JS en scripts --}}
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
