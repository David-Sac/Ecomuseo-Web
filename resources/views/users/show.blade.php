@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/users-show.css') }}">
@endsection

@section('content')
<div class="intranet-main">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <div class="float-start">Información del Usuario</div>
          <div class="float-end">
            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
          </div>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label">Nombre:</label>
            <div class="col-md-6">{{ $user->name }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label">DNI:</label>
            <div class="col-md-6">{{ $user->dni }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label">Teléfono:</label>
            <div class="col-md-6">{{ $user->phone }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label">Fecha de Nacimiento:</label>
            <div class="col-md-6">
              {{ $user->birthdate
                   ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y')
                   : 'N/A' }}
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label">Email:</label>
            <div class="col-md-6">{{ $user->email }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label">Rol:</label>
            <div class="col-md-6">
              @foreach($user->getRoleNames() as $role)
                <span class="badge bg-primary">{{ $role }}</span>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
