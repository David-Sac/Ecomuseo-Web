@extends('layouts.app_new')

@section('styles')
  <!-- Cargamos el CSS específico para el formulario de crear tarea -->
  <link rel="stylesheet" href="{{ asset('css/intranet/tasks-create.css') }}">
@endsection

@section('content')
<div class="intranet-main">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Agregar Tarea</span>
          <!-- Botón Volver a la lista de tareas -->
          <a href="{{ route('tasks.index') }}" class="btn btn-light btn-sm">
            &larr; Volver
          </a>
        </div>
        <div class="card-body">
          <form action="{{ route('tasks.store') }}" method="post">
            @csrf

            {{-- Tipo de tarea --}}
            <div class="mb-3 row">
              <label for="type" class="col-md-4 col-form-label text-md-end">Tipo</label>
              <div class="col-md-6">
                <select id="type" name="type"
                        class="form-control @error('type') is-invalid @enderror"
                        onchange="filterVolunteers()">
                  <option value="">Seleccione tipo</option>
                  <option value="create-blog">Blog</option>
                  <option value="create-tour">Tour</option>
                  <option value="create-donation">Donaciones</option>
                  <option value="create-component">Mantenimiento de componentes</option>
                </select>
                @error('type')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>

            {{-- Voluntario requerido --}}
            <div class="mb-3 row">
              <label class="col-md-4 col-form-label text-md-end">Requerido para:</label>
              <div class="col-md-6">
                <p id="volunteerTypeRequired" class="form-control-plaintext"></p>
              </div>
            </div>

            {{-- Checklist de Componentes --}}
            <div id="componentChecklist" class="mb-3 row" style="display: none;">
              <label for="components" class="col-md-4 col-form-label text-md-end">Componentes</label>
              <div class="col-md-6">
                @foreach ($components as $component)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           id="component-{{ $component->id }}"
                           name="components[]"
                           value="{{ $component->id }}">
                    <label class="form-check-label" for="component-{{ $component->id }}">
                      {{ $component->titleComponente }}
                    </label>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- Selector de voluntario --}}
            <div class="mb-3 row">
              <label for="volunteer_id" class="col-md-4 col-form-label text-md-end">Asignar a</label>
              <div class="col-md-6">
                <select id="volunteer_id" name="volunteer_id"
                        class="form-control @error('volunteer_id') is-invalid @enderror">
                  <option value="">Seleccione un voluntario</option>
                  @foreach ($volunteers as $volunteer)
                    @php
                      $permissions = $volunteer->roles
                        ->flatMap->permissions
                        ->pluck('name')->unique();
                      $roles = $volunteer->roles->pluck('name');
                    @endphp
                    <option value="{{ $volunteer->id }}"
                            data-permissions="{{ $permissions->join(',') }}"
                            data-roles="{{ $roles->join(',') }}">
                      {{ $volunteer->name }}
                    </option>
                  @endforeach
                </select>
                @error('volunteer_id')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>

            {{-- Título --}}
            <div class="mb-3 row">
              <label for="title" class="col-md-4 col-form-label text-md-end">Título</label>
              <div class="col-md-6">
                <input id="title" name="title" type="text"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}">
                @error('title')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>

            {{-- Contenido --}}
            <div class="mb-3 row">
              <label for="content" class="col-md-4 col-form-label text-md-end">Contenido</label>
              <div class="col-md-6">
                <textarea id="content" name="content" rows="5"
                          class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                @error('content')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>

            {{-- Botón de envío --}}
            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <input type="submit" class="btn btn-primary" value="Asignar tarea">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function filterVolunteers() {
    const type = document.getElementById('type').value;
    const opts = document.querySelectorAll('#volunteer_id option');
    const checklist = document.getElementById('componentChecklist');

    // Mostrar checklist sólo para create-component
    checklist.style.display = type === 'create-component' ? 'flex' : 'none';
    if (type !== 'create-component') {
      checklist.querySelectorAll('.form-check-input').forEach(c => c.checked = false);
    }

    // Filtrar voluntarios por permisos y calcular roles requeridos
    const needed = new Set(), volunteerTypeText = [];
    opts.forEach(opt => {
      if (!opt.value) return;
      const perms = opt.dataset.permissions.split(',');
      const show = perms.includes(type);
      opt.style.display = show ? 'block' : 'none';
      if (show) {
        opt.dataset.roles.split(',').forEach(r => {
          if (['create-blog','create-tour'].includes(type) && r.includes('Volunteer senior')) {
            needed.add('Volunteer senior');
          }
          if (['create-donation','create-component'].includes(type) && r.includes('Volunteer junior')) {
            needed.add('Volunteer junior');
          }
        });
      }
    });
    document.getElementById('volunteerTypeRequired')
      .textContent = needed.size ? Array.from(needed).join(', ') 
                                 : 'No hay voluntarios disponibles';
  }

  document.getElementById('type').addEventListener('change', filterVolunteers);
</script>
@endpush
