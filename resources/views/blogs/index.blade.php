<!--<link rel="stylesheet" href="{{ asset('css/blog.css') }}">-->
<!-- Vista index de blogs -->
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
            <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i>Añadir Nuevo Blog</a>
        @endcan
        <table id="table">
            <thead>
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Titulo</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Componentes</th>
                    <th scope="col">Estado</th> <!-- Añade la columna de estado -->
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
                    <td>{{ ucfirst($blog->status) }}</td> <!-- Muestra el estado del blog -->
                    <td>{{ $blog->created_at->format('Y-m-d') }}</td>
                    <td>
                        @can('approve_post')
                            <!-- Botón para aprobar -->
                            <button type="button" class="btn btn-success bi-check-lg approve-btn" data-id="{{ $blog->id }}"></button>
                            <!-- Botón para declinar -->
                            <button type="button" class="btn btn-danger bi-x-lg decline-btn" data-id="{{ $blog->id }}"></button>
                        @endcan

                        <!-- Acciones dependiendo del estado del blog -->
                        <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Mostrar</a>
                        @can('edit-blog')
                            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>Editar</a>
                        @endcan
                        <form action="{{ route('blogs.destroy', $blog->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            @can('delete-blog')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog post?');"><i class="bi bi-trash"></i>Eliminar</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                <td colspan="7">
                    <span class="text-danger">
                        <strong>No Blog Posts Found!</strong>
                    </span>
                </td>
                @endforelse
            </tbody>
        </table>
        {{ $blogs->links() }}
    </div>
</div>
@endsection

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


