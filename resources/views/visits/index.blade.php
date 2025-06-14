@extends('layouts.app_new')
@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/visits.css') }}">
@endsection

@section('content')
<main class="intranet-main">
  <div class="card visits-card">
    <div class="card-header">Lista de Visitas</div>
    <div class="card-body">

      <div class="table-responsive">
        <table id="table" class="table table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Usuario</th>
              <th>Tour</th>
              <th>Personas</th>
              <th>Teléfono</th>
              <th>Acompañantes</th>
              <th>Fecha Seleccionada</th>
              <th>Estado</th>
              <th>Fecha Solicitud</th>
              <th>Fecha Aprobación</th>
              <th style="width:160px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($visits as $visit)
            <tr class="{{ $visit->status }}">
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $visit->user->name }}</td>
              <td>{{ $visit->tourSchedule->tour->name }}</td>
              <td>{{ $visit->number_of_people }}</td>
              <td>{{ json_decode($visit->additional_info,true)['contact_number'] ?? 'No registrado' }}</td>
              <td>
                @php $c = json_decode($visit->additional_info,true)['companions'] ?? []; @endphp
                {{ empty($c) ? 'No registrado' : collect($c)->pluck('name')->implode(', ') }}
              </td>
              <td>{{ $visit->tourSchedule->day_of_week }} {{ date('g:i A', strtotime($visit->tourSchedule->start_time)) }}</td>
              <td>{{ ucfirst($visit->status) }}</td>
              <td>{{ $visit->requested_date }}</td>
              <td>{{ $visit->approved_date ?: 'N/A' }}</td>
              <td>
                <button class="btn btn-sm btn-success bi-check-lg approve-btn" data-id="{{ $visit->id }}"></button>
                <button class="btn btn-sm btn-danger bi-x-lg decline-btn" data-id="{{ $visit->id }}"></button>
                <a class="btn btn-sm btn-warning" href="{{ route('visits.show',$visit->id) }}">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="11" class="text-center text-danger">
                <strong>No se encuentran visitas</strong>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-center mt-3">
        {{ $visits->links() }}
      </div>

    </div>
  </div>
</main>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    // Verifica si hay mensajes de error o éxito en la sesión
    window.onload = function() {
        var error = "{{ session('error') }}";
        var success = "{{ session('success') }}";

        if (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error,
            });
        } else if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Hecho',
                text: success,
            });
        }
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.approve-btn').click(function(e) {
            e.preventDefault();
            var visitId = $(this).data('id');
            $.post('{{ url('/visits') }}/' + visitId + '/approve', {
                _token: $('meta[name="csrf-token"]').attr('content'),
            }, function(response) {
                window.location.reload();
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText,
                });
            });
        });

        $('.decline-btn').click(function(e) {
            e.preventDefault();
            var visitId = $(this).data('id');
            $.post('{{ url('/visits') }}/' + visitId + '/decline', {
                _token: $('meta[name="csrf-token"]').attr('content'),
            }, function(response) {
                window.location.reload();
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText,
                });
            });
        });
    });
</script>
