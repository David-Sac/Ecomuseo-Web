@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">Entradas del Blog</div>
  <div class="card-body">
    @can('create-blog')
      <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm mb-3">
        <i class="bi bi-plus-circle"></i> Añadir Nuevo Blog
      </a>
    @endcan

    <table id="table" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>S#</th>
          <th>Imagen</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Componentes</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($blogs as $blog)
        <tr class="{{ $blog->status }}">
          <td>{{ $loop->iteration }}</td>
          {{-- Nueva columna de imagen --}}
          <td>
            @if($blog->image_path)
              <img src="{{ asset('storage/'.$blog->image_path) }}"
                   alt="Imagen de {{ $blog->title }}"
                   style="width:80px; height:auto; object-fit:cover; border-radius:4px">
            @else
              <span class="text-muted">– sin imagen –</span>
            @endif
          </td>
          <td>{{ $blog->title }}</td>
          <td>{{ $blog->author->name }}</td>
          <td>
            @foreach($blog->components as $c)
              <span class="badge bg-info">{{ $c->titleComponente }}</span>
            @endforeach
          </td>
          <td>{{ ucfirst($blog->status) }}</td>
          <td>{{ $blog->created_at->format('Y-m-d') }}</td>
          <td>
            @can('approve_post')
              <button class="btn btn-success bi-check-lg approve-btn" data-id="{{ $blog->id }}" title="Aprobar"></button>
              <button class="btn btn-danger bi-x-lg decline-btn" data-id="{{ $blog->id }}" title="Rechazar"></button>
            @endcan
            <a href="{{ route('blogs.show', $blog) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Mostrar</a>
            @can('edit-blog')
              <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Editar</a>
            @endcan
            @can('delete-blog')
              <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i> Eliminar</button>
              </form>
            @endcan
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center">No hay entradas</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $blogs->links() }}
    </div>
  </div>
</div>
@endsection

<!-- NO TOCAR este SCRIPT, es el que ya funciona -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $('.approve-btn').click(function(e) {
      e.preventDefault();
      var blogId = $(this).data('id');
      $.post('{{ url('/blogs') }}/' + blogId + '/approve', {
        _token: $('meta[name="csrf-token"]').attr('content'),
      }, function(response) {
        window.location.reload();
      });
    });

    $('.decline-btn').click(function(e) {
      e.preventDefault();
      var blogId = $(this).data('id');
      $.post('{{ url('/blogs') }}/' + blogId + '/decline', {
        _token: $('meta[name="csrf-token"]').attr('content'),
      }, function(response) {
        window.location.reload();
      });
    });
  });
</script>
