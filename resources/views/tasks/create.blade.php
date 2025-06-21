@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/tasks-create.css') }}">
@endsection

@section('content')
<div class="intranet-main">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Agregar Tarea</span>
          <a href="{{ route('tasks.index') }}" class="btn btn-light btn-sm">&larr; Volver</a>
        </div>
        <div class="card-body">
          <form action="{{ route('tasks.store') }}" method="post">
            @csrf

            {{-- Tipo --}}
            <div class="mb-3 row">
              <label for="type" class="col-md-4 col-form-label text-md-end">Tipo</label>
              <div class="col-md-6">
                <select id="type" name="type"
                        class="form-control @error('type') is-invalid @enderror">
                  <option value="">Seleccione tipo</option>
                  <option value="create-blog">Blog</option>
                  <option value="create-tour">Tour</option>
                  <option value="create-donation">Donaciones</option>
                  <option value="create-component">Mantenimiento de componentes</option>
                </select>
                @error('type') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Componentes (solo create-component) --}}
            <div id="componentChecklist" class="mb-3 row" style="display:none;">
              <label class="col-md-4 col-form-label text-md-end">Componentes</label>
              <div class="col-md-6">
                @foreach($components as $c)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           id="comp-{{ $c->id }}" name="components[]" value="{{ $c->id }}">
                    <label class="form-check-label" for="comp-{{ $c->id }}">
                      {{ $c->titleComponente }}
                    </label>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- Usuario --}}
            <div class="mb-3 row">
              <label for="user_id" class="col-md-4 col-form-label text-md-end">Asignar a</label>
              <div class="col-md-6">
                <select id="user_id" name="user_id"
                        class="form-control @error('user_id') is-invalid @enderror">
                  <option value="">Seleccione un usuario</option>
                  @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                  @endforeach
                </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Título --}}
            <div class="mb-3 row">
              <label for="title" class="col-md-4 col-form-label text-md-end">Título</label>
              <div class="col-md-6">
                <input id="title" name="title" type="text"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}">
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Contenido --}}
            <div class="mb-3 row">
              <label for="content" class="col-md-4 col-form-label text-md-end">Contenido</label>
              <div class="col-md-6">
                <textarea id="content" name="content" rows="5"
                          class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Enviar --}}
            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Asignar tarea</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Mostrar checklist solo para “Mantenimiento de componentes”
document.getElementById('type').addEventListener('change', function(){
  document.getElementById('componentChecklist').style.display =
    this.value === 'create-component' ? 'flex' : 'none';
});
</script>
@endsection
