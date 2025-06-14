@extends('layouts.app_new')

@section('styles')
  <!-- SOLO cargamos este CSS -->
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">Blog Posts</div>
  <div class="card-body">
    @can('create-blog')
      <a href="{{ route('blogs.create') }}"
         class="btn btn-success btn-sm mb-4">
        <i class="bi bi-plus-circle"></i> Add New Blog
      </a>
    @endcan

    <!-- OBSERVA: aquí cambié id="table" por class="table table-striped" -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>S#</th>
          <th>Title</th>
          <th>Author</th>
          <th>Components</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($blogs as $blog)
        <tr class="{{ $blog->status }}">
          <td>{{ $loop->iteration }}</td>
          <td>{{ $blog->title }}</td>
          <td>{{ $blog->author->name }}</td>
          <td>
            @foreach ($blog->components as $c)
              <span class="badge bg-info">{{ $c->titleComponente }}</span>
            @endforeach
          </td>
          <td>{{ ucfirst($blog->status) }}</td>
          <td>{{ $blog->created_at->format('Y-m-d') }}</td>
          <td>
            @can('approve_post')
              <button class="btn btn-success bi-check-lg approve-btn" data-id="{{ $blog->id }}"></button>
              <button class="btn btn-danger bi-x-lg decline-btn" data-id="{{ $blog->id }}"></button>
            @endcan
            <a href="{{ route('blogs.show', $blog) }}" class="btn btn-warning btn-sm">Show</a>
            @can('edit-blog')
              <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary btn-sm">Edit</a>
            @endcan
            @can('delete-blog')
              <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"
                        onclick="return confirm('¿Eliminar este blog?')">
                  Delete
                </button>
              </form>
            @endcan
          </td>
        </tr>
        @empty
          <tr><td colspan="7" class="text-center">No Blog Posts Found!</td></tr>
        @endforelse
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $blogs->links() }}
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $('.approve-btn').click(e => {
    e.preventDefault();
    const id = e.currentTarget.dataset.id;
    $.post(`/blogs/${id}/approve`, { _token: $('meta[name="csrf-token"]').attr('content') })
     .then(()=> location.reload());
  });
  $('.decline-btn').click(e => {
    e.preventDefault();
    const id = e.currentTarget.dataset.id;
    $.post(`/blogs/${id}/decline`, { _token: $('meta[name="csrf-token"]').attr('content') })
     .then(()=> location.reload());
  });
</script>
@endpush
