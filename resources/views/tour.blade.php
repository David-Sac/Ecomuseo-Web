@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/tour.css') }}">
@endsection

@section('content')
<section class="tours" id="tours">
  <div class="tours-header">
    <h1 class="titulo"><span>Nuestras Visitas</span></h1>
    @auth
      <div class="custom-button-container">
        <a href="{{ route('visits.publicVisits') }}" class="custom-button">Mis Tours</a>
      </div>
    @endauth
  </div>
  <p class="subtitle">Ven e interactúa con las bondades naturales que conservamos.</p>

  <div class="box-container">
    @foreach ($tours as $tour)
      <div class="tour-box">
        <div class="tour-image">
          @if ($tour->randomImage)
            <img src="{{ asset($tour->randomImage) }}" alt="Tour image">
          @else
            <img src="{{ asset('path/to/default-image.jpg') }}" alt="Default image">
          @endif
        </div>
        <div class="tour-info">
          <h3>{{ $tour->name }}</h3>
          <p class="tour-desc">{{ $tour->description }}</p>
          <p class="tour-capacity"><strong>Tenemos un aforo máximo de:</strong> {{ $tour->max_people }}</p>
          <p class="tour-dates"><strong>Fechas disponibles:</strong> {{ $tour->start_date }} - {{ $tour->end_date }}</p>
          <p class="tour-days"><strong>Los días disponibles:</strong>
            @foreach ($tour->schedules->unique('day_of_week') as $schedule)
              {{ $schedule->day_of_week }}@if (!$loop->last),@endif
            @endforeach
          </p>
          <p class="tour-hours"><strong>Horarios:</strong>
            @foreach ($tour->schedules as $schedule)
              {{ date('g:i A', strtotime($schedule->start_time)) }} a {{ date('g:i A', strtotime($schedule->end_time)) }}
              (Cupos disponibles: {{ $schedule->available_seats }})
              @if (!$loop->last) / @endif
            @endforeach
          </p>
          <p class="tour-components"><strong>Componentes:</strong>
            @foreach ($tour->components as $component)
              {{ $component->titleComponente }}@if (!$loop->last) / @endif
            @endforeach
          </p>
          <p class="tour-guide">
            <strong>Guía:</strong> {{ $tour->volunteers->first()->name ?? 'No asignado' }}<br>
            <strong>Contacto:</strong> {{ $tour->volunteers->first()->phone ?? 'No disponible' }}
          </p>
          <div class="reserve-button-container">
            @if ($tour->available)
              <a href="#" class="reserve-button" data-tour-id="{{ $tour->id }}"
                 data-tour-name="{{ $tour->name }}"
                 data-tour-schedules="{{ $tour->schedules->map(fn($s)=> $s->id.'|'.$s->day_of_week.' '.date('g:i A',strtotime($s->start_time)).' a '.date('g:i A',strtotime($s->end_time)).'|'.$s->available_seats)->join(',') }}"
                 data-tour-components="{{ $tour->components->pluck('titleComponente')->join(', ') }}">
                Quiero reservar
              </a>
            @else
              <span class="sold-out">Agotado</span>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

@include('tours.reservationModal')

@section('scripts')
  {{-- SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      $('.reserve-button').click(function(e) {
          e.preventDefault();
          const btn = $(this);

          // 1) petición AJAX para comprobar duplicado (lo tienes funcionando)
          $.post("{{ route('visits.checkDuplicate') }}", {
              _token: "{{ csrf_token() }}",
              tour_id: btn.data('tour-id')
          }).done(function(resp) {
              if (resp.exists) {
                  return Swal.fire({
                      icon: 'warning',
                      title: '¡Atención!',
                      text: 'Ya realizaste una reserva para "'+ btn.data('tour-name') +'". Ya no se aceptaran más reservas.',
                  });
              }

              // 2) leemos la lista completa de horarios con .attr()
              const rawSchedules = btn.attr('data-tour-schedules');
              const schedules    = rawSchedules.split(',');

              // ya no usamos btn.data para schedules
              const components = btn.data('tour-components');

              $('#tour_id').val(btn.data('tour-id'));
              $('#reservationModalLabel').text('Reservar Tour: ' + btn.data('tour-name'));
              $('#modal-tour-components').text(components);

              const container = $('#modal-tour-schedules-container');
              container.empty();

              schedules.forEach((item, idx) => {
                  const [id, time, seats] = item.split('|');
                  container.append(`
                    <div class="form-check">
                      <input class="form-check-input selected-schedule"
                             type="radio"
                             name="selected_schedule"
                             id="schedule${idx}"
                             value="${id}">
                      <label class="form-check-label" for="schedule${idx}">
                        ${time} (Cupos disponibles: ${seats})
                      </label>
                    </div>`);
              });

              $('#reservationModal').modal('show');
          });
      });

        $('#add-companion').off('click').on('click', function() {
            var container = $('#companions-container');
            var newCompanion = `
                <div class="companion-group">
                    <input type="text" class="form-control" placeholder="Nombre" name="companions[]">
                    <button type="button" class="btn btn-outline-secondary age-button">Adulto</button>
                    <button type="button" class="btn btn-outline-secondary age-button">Menor de edad</button>
                    <button type="button" class="btn btn-outline-danger remove-companion">-</button>
                </div>`;
            container.append(newCompanion);
        });

        $(document).on('click', '.remove-companion', function() {
            $(this).closest('.companion-group').remove();
        });

        $(document).on('click', '.age-button', function() {
            $(this).siblings('.age-button').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document).on('change', '.selected-schedule', function() {
            $('#tour_schedule_id').val($(this).val());
        });
    });
</script>
@endsection