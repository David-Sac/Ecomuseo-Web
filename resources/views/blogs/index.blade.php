@extends('layouts.app_new')

@section('styles')
  <!-- Cargamos el CSS específico de Blogs -->
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">Entradas del Blog</div>
  <div class="card-body">
    @can('create-blog')
      <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm my-2">
        <i class="bi bi-plus-circle"></i> Añadir Nuevo Blog
      </a>
    @endcan

    <table id="table">
      <thead>
        <tr>
          <th scope="col">N°</th>
          <th scope="col">Título</th>
          <th scope="col">Autor</th>
          <th scope="col">Componentes</th>
          <th scope="col">Estado</th>
          <th scope="col">Fecha</th>
          <th scope="col" style="width: 250px;">Opciones</th>
        </tr>
      </thead>
      <tbody>
  @forelse ($blogs as $blog)
  <tr class="{{ $blog->status }}">
    <th scope="row">{{ $loop->iteration }}</th>
    <td>{{ $blog->title }}</td>
    <td>{{ $blog->author->name }}</td>
    <td>
      @foreach ($blog->components as $component)
        <span class="badge bg-info">{{ $component->titleComponente }}</span>
      @endforeach
    </td>
    <td>
      @switch($blog->status)
        @case('pending')
          Pendiente
          @break
        @case('approved')
          Aprobado
          @break
        @case('rejected')
          Rechazado
          @break
        @default
          {{ ucfirst($blog->status) }}
      @endswitch
    </td>
    <td>{{ $blog->created_at->format('Y-m-d') }}</td>
    <td>
      @can('approve_post')
        <button type="button" class="btn btn-success bi-check-lg approve-btn" data-id="{{ $blog->id }}"></button>
        <button type="button" class="btn btn-danger bi-x-lg decline-btn" data-id="{{ $blog->id }}"></button>
      @endcan

      <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-eye"></i> Mostrar
      </a>
      @can('edit-blog')
        <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">
          <i class="bi bi-pencil-square"></i> Editar
        </a>
      @endcan
      @can('delete-blog')
        <form action="{{ route('blogs.destroy', $blog->id) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este blog?')">
          @csrf
          @method('DELETE')
          <button class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Eliminar
          </button>
        </form>
      @endcan
    </td>
  </tr>
  @empty
    <tr>
      <td colspan="7" class="text-center">No hay entradas</td>
    </tr>
  @endforelse
</tbody>

    </table>

    {{ $blogs->links() }}
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
