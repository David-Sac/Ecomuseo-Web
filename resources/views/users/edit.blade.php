@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/users.css') }}">
@endsection

@section('content')
<div class="intranet-main">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <div class="float-start">Editar Usuario</div>
          <div class="float-end">
            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('users.update', $user->id) }}" method="post">
            @csrf @method('PUT')

            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label">Nombre</label>
              <div class="col-md-6">
                <input
                  type="text"
                  id="name"
                  name="name"
                  value="{{ old('name', $user->name) }}"
                  class="form-control @error('name') is-invalid @enderror"
                >
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label">Email</label>
              <div class="col-md-6">
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="{{ old('email', $user->email) }}"
                  class="form-control @error('email') is-invalid @enderror"
                >
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label">Contraseña</label>
              <div class="col-md-6">
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control @error('password') is-invalid @enderror"
                >
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password_confirmation" class="col-md-4 col-form-label">Confirmar Contraseña</label>
              <div class="col-md-6">
                <input
                  type="password"
                  id="password_confirmation"
                  name="password_confirmation"
                  class="form-control"
                >
              </div>
            </div>

            <div class="mb-3 row">
              <label for="dni" class="col-md-4 col-form-label">DNI</label>
              <div class="col-md-6">
                <input
                  type="text"
                  id="dni"
                  name="dni"
                  value="{{ old('dni', $user->dni) }}"
                  class="form-control @error('dni') is-invalid @enderror"
                >
                @error('dni') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <label for="phone" class="col-md-4 col-form-label">Teléfono</label>
              <div class="col-md-6">
                <input
                  type="text"
                  id="phone"
                  name="phone"
                  value="{{ old('phone', $user->phone) }}"
                  class="form-control @error('phone') is-invalid @enderror"
                >
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <label for="birthdate" class="col-md-4 col-form-label">Fecha de Nacimiento</label>
              <div class="col-md-6">
                <input
                  type="date"
                  id="birthdate"
                  name="birthdate"
                  value="{{ old('birthdate', $user->birthdate) }}"
                  class="form-control @error('birthdate') is-invalid @enderror"
                >
                @error('birthdate') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label">Roles</label>
              <div class="col-md-6">
                @foreach($roles as $role)
                  @if($role!='Super Admin' || Auth::user()->hasRole('Super Admin'))
                    <div class="form-check">
                      <input
                        type="checkbox"
                        name="roles[]"
                        value="{{ $role }}"
                        id="role_{{ $role }}"
                        class="form-check-input @error('roles') is-invalid @enderror"
                        {{ in_array($role, old('roles', $userRoles)) ? 'checked':'' }}
                      >
                      <label class="form-check-label" for="role_{{ $role }}">{{ $role }}</label>
                    </div>
                  @endif
                @endforeach
                @error('roles') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
