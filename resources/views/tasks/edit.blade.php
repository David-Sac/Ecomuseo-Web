@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/blogs-edit.css') }}">
  <link rel="stylesheet" href="{{ asset('css/intranet/tasks-edit.css') }}">
@endsection

@section('content')
<div class="intranet-main">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10">
      <div class="card">
        <div class="card-header">
          <div class="float-start">Editar Tarea</div>
          <div class="float-end">
            <a href="{{ route('tasks.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
          </div>
        </div>

        <div class="card-body">
          <form action="{{ route('tasks.update', $task->id) }}" method="post">
            @csrf
            @method('PUT')

            {{-- 1) Tipo --}}
            <div class="mb-3 row">
              <label for="type" class="col-md-2 col-form-label text-md-end">Tipo</label>
              <div class="col-md-10">
                <select id="type" name="type"
                        class="form-control @error('type') is-invalid @enderror"
                        onchange="filterVolunteers()">
                  <option value="">Seleccione tipo</option>
                  <option value="create-blog"     {{ $task->type==='blog'      ? 'selected':'' }}>Blog</option>
                  <option value="create-tour"     {{ $task->type==='tour'      ? 'selected':'' }}>Tour</option>
                  <option value="create-donation" {{ $task->type==='donation'  ? 'selected':'' }}>Donaciones</option>
                  <option value="create-component"{{ $task->type==='component' ? 'selected':'' }}>Mantenimiento</option>
                </select>
                @error('type') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- 2) Volunteer senior/junior --}}
            <div class="mb-3 row">
              <label class="col-md-2 col-form-label text-md-end">Requerido para:</label>
              <div class="col-md-10">
                <p id="volunteerTypeRequired" class="form-control-plaintext"></p>
              </div>
            </div>

            {{-- 3) Componentes --}}
            <div class="mb-3 row" id="componentChecklist" style="display:none;">
              <label class="col-md-2 col-form-label text-md-end">Componentes</label>
              <div class="col-md-10">
                <div class="row">
                  @foreach($components as $c)
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="comp{{ $c->id }}"
                               name="components[]"
                               value="{{ $c->id }}"
                               {{ in_array($c->id, $selectedComponents) ? 'checked' : '' }}>
                        <label class="form-check-label" for="comp{{ $c->id }}">
                          {{ $c->titleComponente }}
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            {{-- 4) Asignar a --}}
            <div class="mb-3 row">
              <label for="volunteer_id" class="col-md-2 col-form-label text-md-end">Asignar a</label>
              <div class="col-md-10">
                <select id="volunteer_id" name="volunteer_id"
                        class="form-control @error('volunteer_id') is-invalid @enderror">
                  <option value="">Seleccione un voluntario</option>
                  @foreach($volunteers as $v)
                    @php
                      $perms = $v->roles->flatMap->permissions->pluck('name')->join(',');
                      $roles = $v->roles->pluck('name')->join(',');
                    @endphp
                    <option value="{{ $v->id }}"
                            data-permissions="{{ $perms }}"
                            data-roles="{{ $roles }}"
                            {{ $v->id == $volunteerId ? 'selected':'' }}>
                      {{ $v->name }}
                    </option>
                  @endforeach
                </select>
                @error('volunteer_id') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- 5) Título --}}
            <div class="mb-3 row">
              <label for="title" class="col-md-2 col-form-label text-md-end">Título</label>
              <div class="col-md-10">
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title', $task->title) }}"
                       class="form-control @error('title') is-invalid @enderror">
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- 6) Contenido --}}
            <div class="mb-3 row">
              <label for="content" class="col-md-2 col-form-label text-md-end">Contenido</label>
              <div class="col-md-10">
                <textarea id="content"
                          name="content"
                          rows="5"
                          class="form-control @error('content') is-invalid @enderror">{{ old('content', $task->content) }}</textarea>
                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- 7) Botón --}}
            <div class="mb-3 row">
              <div class="col-md-10 offset-md-2">
                <button type="submit" class="btn btn-primary">Actualizar tarea</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function filterVolunteers() {
    const type = document.getElementById('type').value;
    const checklist = document.getElementById('componentChecklist');
    checklist.style.display = type==='create-component' ? 'block' : 'none';

    if(type!=='create-component'){
      document.querySelectorAll('#componentChecklist .form-check-input')
              .forEach(i=> i.checked=false);
    }

    const opts = document.querySelectorAll('#volunteer_id option');
    const needed = new Set();
    opts.forEach(opt=>{
      if(!opt.value) return;
      const perms = opt.dataset.permissions.split(',');
      const show = perms.includes(type);
      opt.style.display = show?'block':'none';
      if(show){
        opt.dataset.roles.split(',').forEach(r=>{
          if((type==='create-blog'||type==='create-tour') && r.includes('Volunteer senior'))
            needed.add('Volunteer senior');
          if((type==='create-donation'||type==='create-component') && r.includes('Volunteer junior'))
            needed.add('Volunteer junior');
        });
      }
    });
    document.getElementById('volunteerTypeRequired').textContent =
      needed.size
        ? Array.from(needed).join(', ')
        : 'No hay voluntarios disponibles';
  }

  document.addEventListener('DOMContentLoaded', filterVolunteers);
  document.getElementById('type').addEventListener('change', filterVolunteers);
</script>
@endsection
