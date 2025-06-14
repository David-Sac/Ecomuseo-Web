@extends('layouts.app_new')

@section('styles')
  <!-- CSS específico -->
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs-edit.css') }}">
  <!-- EasyMDE -->
  <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
@endsection

@section('content')
<div class="intranet-main">
  <div class="card">
    <div class="card-header">
      <div class="float-start">Editar Blog</div>
      <div class="float-end">
        <a href="{{ route('blogs.index') }}" class="btn btn-light btn-sm">&larr; Volver a Blogs</a>
      </div>
    </div>
    <div class="card-body">
      {{-- IMPORTANTE: enctype para manejar archivos --}}
      <form action="{{ route('blogs.update', $blog->id) }}" method="post" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Título --}}
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Título</label>
          <div class="col-md-6">
            <input type="text"
                   name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $blog->title) }}">
            @error('title')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        {{-- Contenido --}}
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Contenido</label>
          <div class="col-md-6">
            <textarea id="content"
                      name="content"
                      rows="8"
                      class="form-control @error('content') is-invalid @enderror">{{ old('content', $blog->content) }}</textarea>
            @error('content')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        {{-- Componentes --}}
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Componentes</label>
          <div class="col-md-6">
            @foreach($components as $c)
              <div class="form-check">
                <input type="checkbox"
                       name="components[]"
                       value="{{ $c->id }}"
                       id="comp{{ $c->id }}"
                       class="form-check-input"
                       {{ in_array($c->id, old('components', $blog->components->pluck('id')->toArray())) ? 'checked' : '' }}>
                <label for="comp{{ $c->id }}" class="form-check-label">
                  {{ $c->titleComponente }}
                </label>
              </div>
            @endforeach
            @error('components')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        {{-- Imagen actual y campo para nueva --}}
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Imagen Actual</label>
          <div class="col-md-6">
            @if($blog->image_path)
              <img src="{{ asset('storage/'.$blog->image_path) }}" alt="Imagen del blog" class="img-fluid mb-2" style="max-height:150px;">
            @else
              <em>No hay imagen asignada</em>
            @endif
          </div>
        </div>

        <div class="mb-3 row">
          <label for="image_path" class="col-md-4 col-form-label text-md-end">Cambiar Imagen</label>
          <div class="col-md-6">
            <input type="file"
                   id="image_path"
                   name="image_path"
                   accept="image/*"
                   class="form-control @error('image_path') is-invalid @enderror">
            @error('image_path')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        {{-- Estado (solo Admin/Super Admin) --}}
        @canany(['Super Admin','Admin'])
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end">Estado</label>
            <div class="col-md-6">
              <select name="status" class="form-control">
                <option value="pending"  {{ $blog->status=='pending'  ? 'selected':'' }}>Pendiente</option>
                <option value="approved" {{ $blog->status=='approved' ? 'selected':'' }}>Aprobado</option>
                <option value="rejected" {{ $blog->status=='rejected' ? 'selected':'' }}>Rechazado</option>
              </select>
            </div>
          </div>
        @endcanany

        {{-- Botón de envío --}}
        <div class="mb-3 row">
          <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">Actualizar Blog</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      new EasyMDE({ element: document.getElementById('content') });
    });
  </script>
@endsection
