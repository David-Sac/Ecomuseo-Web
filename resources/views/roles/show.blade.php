@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/roles-show.css') }}">
@endsection

@section('content')
<div class="intranet-main"><!-- Contenedor flex -->
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <div class="float-start">
            Role Information
          </div>
          <div class="float-end">
            <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
          </div>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end"><strong>Name:</strong></label>
            <div class="col-md-6">
              {{ $role->name }}
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end"><strong>Permissions:</strong></label>
            <div class="col-md-6">
              @if ($role->name == 'Super Admin')
                <span class="badge bg-primary">All</span>
              @else
                @forelse ($rolePermissions as $permission)
                  <span class="badge bg-primary">{{ $permission->name }}</span>
                @empty
                  <em>No permissions</em>
                @endforelse
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
