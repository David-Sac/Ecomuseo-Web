@extends('layouts.app_new')
@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/user-create.css') }}">
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Añadir Nuevo Usuario
                </div>
                <div class="float-end">
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Volver</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf

                    <!-- Campo Nombre -->
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Campo Email -->
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Contraseña</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Campo Confirmar Contraseña -->
                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirmar Contraseña</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <!-- Campo DNI -->
                    <div class="mb-3 row">
                        <label for="dni" class="col-md-4 col-form-label text-md-end text-start">DNI</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni" name="dni" value="{{ old('dni') }}">
                            @if ($errors->has('dni'))
                                <span class="text-danger">{{ $errors->first('dni') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Campo Teléfono -->
                    <div class="mb-3 row">
                        <label for="phone" class="col-md-4 col-form-label text-md-end text-start">Teléfono</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Campo Fecha de Nacimiento -->
                    <div class="mb-3 row">
                        <label for="birthdate" class="col-md-4 col-form-label text-md-end text-start">Fecha de Nacimiento</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                            @if ($errors->has('birthdate'))
                                <span class="text-danger">{{ $errors->first('birthdate') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Campo Rol -->
                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6">
                            @forelse ($roles as $role)
                                @if ($role != 'Super Admin')
                                    <div class="form-check">
                                        <input class="form-check-input @error('roles') is-invalid @enderror" type="checkbox" name="roles[]" value="{{ $role }}" id="role_{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role }}">
                                            {{ $role }}
                                        </label>
                                    </div>
                                @else
                                    @if (Auth::user()->hasRole('Super Admin'))
                                        <div class="form-check">
                                            <input class="form-check-input @error('roles') is-invalid @enderror" type="checkbox" name="roles[]" value="{{ $role }}" id="role_{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role }}">
                                                {{ $role }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            @empty
                                <p>No hay roles disponibles</p>
                            @endforelse
                            @if ($errors->has('roles'))
                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div>


                    <!-- Botón Añadir Usuario -->
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Añadir Usuario">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
