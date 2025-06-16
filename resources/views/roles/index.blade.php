{{-- resources/views/roles/index.blade.php --}}
@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/roles.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="float-start">
      Administrar Roles
    </div>
    <div class="float-end">
      {{-- Botón para volver a la tabla de usuarios --}}
      <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
        &larr; Volver a Usuarios
      </a>
    </div>
  </div>

  <div class="card-body">
    @can('create-role')
      <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm my-2">
        <i class="bi bi-plus-circle"></i> Añadir Nuevo Rol
      </a>
    @endcan

    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>N°</th>
          <th>Rol</th>
          <th style="width: 250px;">Opciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($roles as $role)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $role->name }}</td>
            <td>
              <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                @csrf
                @method('DELETE')

                <a href="{{ route('roles.show', $role->id) }}"
                   class="btn btn-warning btn-sm">
                  <i class="bi bi-eye"></i> Mostrar
                </a>

                @can('edit-role')
                  <a href="{{ route('roles.edit', $role->id) }}"
                     class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square"></i> Editar
                  </a>
                @endcan

                @can('delete-role')
                  {{-- No permitir eliminar tu propio rol --}}
                  @if(!$role->users->contains(auth()->id()))
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar este rol?');">
                      <i class="bi bi-trash"></i> Eliminar
                    </button>
                  @endif
                @endcan
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="text-center text-danger">
              <strong>No se encontraron roles</strong>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{ $roles->links() }}
  </div>
</div>
@endsection
