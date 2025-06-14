@extends('layouts.app_new')

@section('styles')
  <!-- Cargamos el CSS específico -->
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs-edit.css') }}">
  <!-- EasyMDE -->
  <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
@endsection

@section('content')
<div class="intranet-main"><!-- <-- aquí -->
  <div class="card">
    <div class="card-header">
      <div class="float-start">Edit Blog</div>
      <div class="float-end">
        <a href="{{ route('blogs.index') }}" class="btn btn-light btn-sm">
          &larr; Volver a Blogs
        </a>
      </div>
    </div>
    <div class="card-body">
      <form action="{{ route('blogs.update', $blog->id) }}" method="post">
        @csrf @method('PUT')

        <!-- Title -->
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Title</label>
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

        <!-- Content -->
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Content</label>
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

        <!-- Components -->
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end">Components</label>
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

        <!-- Status (si eres Admin/Super Admin) -->
        @canany(['Super Admin','Admin'])
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end">Status</label>
            <div class="col-md-6">
              <select name="status" class="form-control">
                <option value="pending"  {{ $blog->status=='pending'  ? 'selected':'' }}>Pending</option>
                <option value="approved" {{ $blog->status=='approved' ? 'selected':'' }}>Approved</option>
                <option value="rejected" {{ $blog->status=='rejected' ? 'selected':'' }}>Rejected</option>
              </select>
            </div>
          </div>
        @endcanany

        <!-- Submit -->
        <div class="mb-3 row">
          <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">Update Blog</button>
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
