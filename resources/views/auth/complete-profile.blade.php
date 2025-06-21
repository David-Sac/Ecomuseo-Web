{{-- resources/views/complete-profile.blade.php --}}
@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/complete-profile.css') }}">
@endsection

@section('content')
<main class="main-content">
  <div class="container mt-5">
    <h2 class="mb-4">Complete su perfil para continuar</h2>
    <form method="POST" action="{{ route('complete-profile.store') }}">
      @csrf

      <div class="form-group">
        <label for="dni">DNI</label>
        <input type="text"
               class="form-control @error('dni') is-invalid @enderror"
               id="dni"
               name="dni"
               value="{{ old('dni') }}"
               required>
        @error('dni')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="text"
               class="form-control @error('phone') is-invalid @enderror"
               id="phone"
               name="phone"
               value="{{ old('phone') }}"
               required>
        @error('phone')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div class="form-group">
        <label for="birthdate">Fecha de Nacimiento</label>
        <input type="date"
               class="form-control @error('birthdate') is-invalid @enderror"
               id="birthdate"
               name="birthdate"
               value="{{ old('birthdate') }}"
               required>
        @error('birthdate')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
  </div>
</main>
@endsection
    