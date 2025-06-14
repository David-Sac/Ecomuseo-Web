@extends('layouts.app_new')
@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/users.css') }}">
@endsection
@section('content')

<div class="card">
    <div class="card-header">Gestiona Usuarios</div>
    <div class="card-body">
        @can('create-user')
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Añadir Nuevo Usuario</a>
        @endcan
        @can(['create-role', 'edit-role', 'delete-role'])
            <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm my-2">
            <i class="bi bi-person-fill-gear"></i> Gestionar Roles y Permisos</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">DNI</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Fecha de Nacimiento</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->dni }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @forelse ($user->getRoleNames() as $role)
                            <span class="badge bg-primary">{{ $role }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td>
                        <form action="{{ route('users.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Mostrar</a>

                            @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Editar</a>
                                @endif
                            @else
                                @can('edit-user')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Editar</a>
                                @endcan

                                @can('delete-user')
                                    @if (Auth::user()->id != $user->id)
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Quieres eliminar este usuario?');"><i class="bi bi-trash"></i> Eliminar</button>
                                    @endif
                                @endcan
                            @endif

                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="8">
                        <span class="text-danger">
                            <strong>No se encontraron usuarios</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}

    </div>
</div>

@endsection
