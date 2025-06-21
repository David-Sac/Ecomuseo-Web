@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/home.css') }}">
@endsection

@section('content')
<div class="dashboard-container container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Panel Principal') }}</div>
        <div class="card-body text-center">

          @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
          @endif

          @php
              // 1) Calculamos si el usuario tiene AL MENOS un permiso de los del panel
              $hasActions = Auth::user()->canAny([
                  'create-user','edit-user','delete-user',
                  'create-component','edit-component','delete-component',
                  'create-tour','edit-tour','delete-tour',
                  'create-blog','edit-blog','delete-blog',
                  'edit-donation','delete-donation',
                  'create-volunteer','edit-volunteer','delete-volunteer'
              ]);
          @endphp

          {{-- 2) Si tiene permisos, muestro botones; si no, muestro alerta --}}
          @if ($hasActions)
            <div class="dashboard-actions">
              @canany(['create-user','edit-user','delete-user'])
                <a class="btn btn-success btn-lg" href="{{ route('users.index') }}">
                  <i class="bi bi-people"></i> Usuarios
                </a>
              @endcanany

              @canany(['create-component','edit-component','delete-component'])
                <a class="btn btn-warning btn-lg" href="{{ route('components.index') }}">
                  <i class="bi bi-house-gear"></i> Componentes
                </a>
              @endcanany

              @canany(['create-tour','edit-tour','delete-tour'])
                <a class="btn btn-secondary btn-lg" href="{{ route('tours.index') }}">
                  <i class="bi bi-bezier2"></i> Tours
                </a>
              @endcanany

              @canany(['create-blog','edit-blog','delete-blog'])
                <a class="btn btn-primary btn-lg" href="{{ route('blogs.index') }}">
                  <i class="bi bi-newspaper"></i> Blogs
                </a>
              @endcanany

              @canany(['edit-donation','delete-donation'])
                <a class="btn btn-success btn-lg" href="{{ route('donations.show') }}">
                  <i class="bi bi-coin"></i> Donaciones
                </a>
              @endcanany

              @canany(['create-volunteer','edit-volunteer','delete-volunteer'])
                <a class="btn btn-danger btn-lg" href="{{ route('volunteers.show') }}">
                  <i class="bi bi-wrench"></i> Voluntarios
                </a>
              @endcanany
            </div>
          @else
            <div class="alert alert-info my-4">
              <strong>Acceso restringido:</strong><br>
              El administrador aún no te ha otorgado permisos para usar ninguna sección del panel.<br>
              Por favor, espera su aprobación o contáctalo si crees que debería darte acceso.
            </div>
          @endif

          {{-- (tu sección de tareas para voluntarios sigue igual) --}}
          @if (Auth::user()->hasAnyRole(['Volunteer junior','Volunteer senior']))
            <hr>
            @if ($tasks->isNotEmpty())
              <div class="alert alert-primary"><strong>Mis Tareas Pendientes:</strong></div>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Tipo</th>
                      <th>Componentes</th>
                      <th>Título</th>
                      <th>Contenido</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tasks as $task)
                      <tr>
                        <td>{{ ucfirst($task->type) }}</td>
                        <td>
                          @forelse ($task->componentDetails as $c)
                            <span>{{ $c->titleComponente }}</span><br>
                          @empty
                            <span class="text-muted">—</span>
                          @endforelse
                        </td>
                        <td>{{ ucfirst($task->title) }}</td>
                        <td>{{ ucfirst($task->content) }}</td>
                        <td>{{ ucfirst($task->pivot->status) }}</td>
                        <td>
                          @if ($task->pivot->status === 'pending')
                            <button 
                              class="btn btn-sm btn-success bi-check-lg complete-btn" 
                              data-id="{{ $task->id }}" 
                              title="Marcar como completada">
                            </button>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p>No tienes tareas asignadas.</p>
            @endif
          @endif

        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.querySelectorAll('.complete-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      const id = btn.dataset.id;
      fetch(`/tasks/${id}/complete`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
        }
      }).then(() => location.reload());
    });
  });
</script>
@endpush

@endsection
