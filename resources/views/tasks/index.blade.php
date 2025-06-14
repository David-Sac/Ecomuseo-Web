@extends('layouts.app_new')

@section('styles')
  <!-- Quitamos el <link> fuera de sección y cargamos nuestro CSS de tareas -->
  <link rel="stylesheet" href="{{ asset('css/intranet/tasks.css') }}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">Lista de Tareas</div>
  <div class="card-body">

    @can('create-task')
      <a href="{{ route('tasks.create') }}"
         class="btn btn-success btn-sm my-2">
         <i class="bi bi-plus-circle"></i> Añadir Nueva Tarea
      </a>
    @endcan

    <div class="d-flex justify-content-end">
      <!-- daterange y botón -->
      <div id="reportrange"></div>
      <form action="{{ route('tasks.export') }}" method="post" class="ms-2">
        @csrf @method('POST')
        <input type="hidden" name="start_date" id="start_date">
        <input type="hidden" name="end_date" id="end_date">
        <button class="btn btn-secondary btn-sm">Generar Reporte</button>
      </form>
    </div>

    <table id="table" class="table table-striped mt-4">
      <thead>
        <tr>
          <th>#</th><th>Tipo</th><th>Título</th><th>Contenido</th>
          <th>Componentes</th><th>Estado</th><th>Asignación</th>
          <th>Completado</th><th>Voluntario</th><th>Acciones</th>
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
            </td>
            @if($v = $task->volunteers->first()->pivot)
              <td>{{ ucfirst($v->status) }}</td>
              <td>{{ \Carbon\Carbon::parse($v->assigned_date)->format('d/m/Y') }}</td>
              <td>{{ $v->completed_date ? \Carbon\Carbon::parse($v->completed_date)->format('d/m/Y') : 'N/A' }}</td>
              <td>{{ $task->volunteers->first()->name }}</td>
            @else
              <td colspan="4">No asignado</td>
            @endif
            <td>
              @if(isset($v) && $v->status==='pending')
                @can('edit-task')
                  <a href="{{ route('tasks.edit',$task->id) }}" class="btn btn-primary btn-sm">Editar</a>
                @endcan
                @can('delete-task')
                  <form action="{{ route('tasks.destroy',$task->id) }}"
                        method="post" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Cancelar tarea?')">
                      Cancelar
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
    $(function() {
        var start = moment().subtract(6, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            $('#start_date').val(start.format('YYYY-MM-DD'));
            $('#end_date').val(end.format('YYYY-MM-DD'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            alwaysShowCalendars: true,
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                weekLabel: 'S',
                daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                    'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ],
                firstDay: 1,
                customRangeLabel: 'Personalizado',
                showCustomRangeLabel: true,
                showWeekNumbers: true,
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 15 días': [moment().subtract(14, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')],
                'Este año': [moment().startOf('year'), moment().endOf('year')],
                'Año pasado': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1,
                    'year').endOf('year')],
            }
        }, cb);

        cb(start, end);
    });

    // Manejar solicitudes AJAX para aprobar y declinar tareas
    $(document).ready(function() {
        $('.approve-btn').click(function() {
            var taskId = $(this).data('id');
            $.ajax({
                url: '{{ url('/tasks') }}/' + taskId + '/approve',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert('Error al aprobar la tarea.');
                    }
                }
            });
        });

        $('.decline-btn').click(function() {
            var taskId = $(this).data('id');
            $.ajax({
                url: '{{ url('/tasks') }}/' + taskId + '/decline',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert('Error al declinar la tarea.');
                    }
                }
            });
        });
    });
</script>
