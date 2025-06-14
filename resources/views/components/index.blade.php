@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/components.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">Lista de Componentes</div>
  <div class="card-body">
    @can('create-component')
      <a href="{{ route('components.create') }}" class="btn btn-success btn-sm my-2">
        <i class="bi bi-plus-circle"></i> Añadir Componente
      </a>
    @endcan

    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>S#</th>
          <th>Título</th>
          <th>Descripción</th>
          <th>Imagen</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($components as $component)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $component->titleComponente }}</td>
          <td>{{ $component->description }}</td>
          <td>
            @if($component->rutaImagenComponente)
              <img
                src="{{ asset($component->rutaImagenComponente) }}"
                width="80"
                style="object-fit:cover;"
                alt="Imagen"
              >
            @endif
          </td>
          <td>
            <a href="{{ route('components.show', $component) }}" class="btn btn-warning btn-sm">
              <i class="bi bi-eye"></i> Mostrar
            </a>
            @can('edit-component')
              <a href="{{ route('components.edit', $component) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square"></i> Editar
              </a>
            @endcan
            @can('delete-component')
              <form action="{{ route('components.destroy', $component) }}"
                    method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar este componente?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </form>
            @endcan
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-danger">
            No hay componentes aún.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $components->links() }}
    </div>
  </div>
</div>
@endsection
