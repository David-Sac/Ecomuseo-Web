{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/profile.css') }}">
@endsection

@section('content')
<main class="intranet-main">

  {{-- Mensaje de error genérico --}}
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="row justify-content-center">
    <div class="col-lg-8">

      {{-- Información de Perfil --}}
      <div class="card mb-4">
        <div class="card-header">Información de Perfil</div>
        <div class="card-body">
          @include('profile.partials.update-profile-information-form')
        </div>
      </div>

      {{-- Cambiar Contraseña --}}
      <div class="card mb-4">
        <div class="card-header">Cambiar Contraseña</div>
        <div class="card-body">
          @include('profile.partials.update-password-form')
        </div>
      </div>

      {{-- Eliminar Cuenta: solo si NO es Super Admin --}}
      @if (! auth()->user()->hasRole('Super Admin'))
        <div class="card mb-4">
          <div class="card-header">Eliminar Cuenta</div>
          <div class="card-body">
            @include('profile.partials.delete-user-form')
          </div>
        </div>
      @endif

    </div>
  </div>

</main>
@endsection
