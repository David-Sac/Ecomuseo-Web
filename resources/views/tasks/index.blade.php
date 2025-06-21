@extends('layouts.app_new')

@section('styles')
  <!-- Cargamos el CSS específico de tareas -->
  <link rel="stylesheet" href="{{ asset('css/intranet/tasks.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Lista de Tareas</span>
    <!-- Botón Volver a la página anterior -->
    <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
      &larr; Volver
    </a>
  </div>
  
  <div class="card-body">

    @can('create-task')
      <a href="{{ route('tasks.create') }}" class="btn btn-success btn-sm my-2">
        <i class="bi bi-plus-circle"></i> Añadir Nueva Tarea
      </a>
    @endcan

    <div class="d-flex justify-content-end">
      <!-- daterange y botón de exportación -->
      <div id="reportrange"></div>
      <form action="{{ route('tasks.export') }}" method="post" class="ms-2">
        @csrf
        <input type="hidden" name="start_date" id="start_date">
        <input type="hidden" name="end_date" id="end_date">
        <button class="btn btn-secondary btn-sm">Generar Reporte</button>
      </form>
    </div>

    <table id="table" class="table table-striped mt-4">
      <thead>
        <tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Título</th>
          <th>Contenido</th>
          <th>Componentes</th>
          <th>Estado</th>
          <th>Asignación</th>
          <th>Completado</th>
          <th>Voluntario</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tasks as $task)
          <tr class="{{ $task->volunteers->first()->pivot->status ?? '' }}">
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ ucfirst($task->type) }}</td>
            <td>{{ $task->title }}</td>
            <td>{{ $task->content }}</td>
            <td>
              @forelse($task->componentDetails as $c)
                {{ $c->titleComponente }}<br>
              @empty
                N/A
              @endforelse
            <td>
              @php $pivot = $task->volunteers->first()->pivot; @endphp

              {{-- Si está pendiente: un botón “Cancelar” --}}
              @if($pivot->status === 'pending')
                @can('delete-task')
                  <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-warning btn-sm"
                            onclick="return confirm('¿Seguro que quieres cancelar esta tarea?')">
                      Cancelar
                    </button>
                  </form>
                @endcan

              {{-- Si está completada o ya cancelada: un botón “Eliminar” definitivo --}}
              @else
                @can('delete-task')
                  <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar esta tarea de forma permanente?')">
                      Eliminar
                    </button>
                  </form>
                @endcan
              @endif
            </td>


          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-danger text-center">
              <strong>No se encontraron tareas!</strong>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{ $tasks->links() }}

  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script>
$(function(){
  // Cancelar tarea (pending → cancelled + task inactive)
  $('.cancel-btn').on('click', function(){
    const id = $(this).data('id');
    if (!confirm('¿Seguro que quieres cancelar esta tarea?')) return;
    $.ajax({
      url: `/tasks/${id}`,
      method: 'DELETE',
      data: { _token: '{{ csrf_token() }}' }
    }).done(() => location.reload());
  });

  // Eliminar tarea (completed/cancelled → borrado lógico)
  $('.delete-btn').on('click', function(){
    const id = $(this).data('id');
    if (!confirm('¿Eliminar definitivamente esta tarea?')) return;
    $.ajax({
      url: `/tasks/${id}`,
      method: 'DELETE',
      data: { _token: '{{ csrf_token() }}' }
    }).done(() => location.reload());
  });
});
</script>
@endsection

