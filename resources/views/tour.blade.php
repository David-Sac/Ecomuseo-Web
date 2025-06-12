<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_vectorizado.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/tour.css') }}">
    <script src="{{ asset('js/welcome.js') }}"></script>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <title>Ecomuseo LLAQTA AMARU -YOREN KUWAI</title>
</head>

<body>
            @extends('layouts.app_new')


    <!-- Sección de tours -->
    <section class="tours" id="tours">
        <h1 class="titulo">
            <center><span>Nuestras Visitas</span>
        </h1>
        </center>
        <center><a>Ven e interactúa con las bondades naturales que conservamos.</a></center>
        @auth
            <div class="custom-button-container">
                <a href="{{ route('visits.publicVisits') }}" class="custom-button">Mis Tours</a>
            </div>
        @endauth

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
                                {{ $schedule->day_of_week }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                        <p class="tour-hours"><strong>Horarios:</strong>
                            @foreach ($tour->schedules as $schedule)
                                {{ date('g:i A', strtotime($schedule->start_time)) }} a
                                {{ date('g:i A', strtotime($schedule->end_time)) }} (Cupos disponibles:
                                {{ $schedule->available_seats }})@if (!$loop->last)
                                    /
                                @endif
                            @endforeach
                        </p>
                        <p class="tour-components"><strong>Los componentes que visitarás serán:</strong>
                            @foreach ($tour->components as $component)
                                {{ $component->titleComponente }}@if (!$loop->last)
                                    /
                                @endif
                            @endforeach
                        </p>
                        <p class="tour-guide">
                            <strong>El guía para esta aventura será:</strong>
                            {{ $tour->volunteers->first()->name ?? 'No asignado' }}<br>
                            <strong>Su número de contacto es:</strong>
                            {{ $tour->volunteers->first()->phone ?? 'No disponible' }}
                        </p>
                        <div class="reserve-button-container">
                            @if ($tour->available)
                                <a href="#" class="reserve-button" data-tour-id="{{ $tour->id }}"
                                    data-tour-name="{{ $tour->name }}"
                                    data-tour-schedules="{{ $tour->schedules->map(fn($s) => $s->id . '|' . $s->day_of_week . ' ' . date('g:i A', strtotime($s->start_time)) . ' a ' . date('g:i A', strtotime($s->end_time)) . '|' . $s->available_seats)->join(',') }}"
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

</body>

</html>

@include('tours.reservationModal')

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.reserve-button').click(function() {
            var tourId = $(this).data('tour-id');
            var tourName = $(this).data('tour-name');
            var tourSchedules = $(this).data('tour-schedules').split(',');
            var tourComponents = $(this).data('tour-components');

            $('#tour_id').val(tourId);
            $('#reservationModalLabel').text('Reservar Tour: ' + tourName);
            $('#modal-tour-components').text(tourComponents);

            var schedulesContainer = $('#modal-tour-schedules-container');
            schedulesContainer.empty();

            tourSchedules.forEach(function(schedule, index) {
                var scheduleDetails = schedule.split('|');
                var scheduleId = scheduleDetails[0];
                var scheduleTime = scheduleDetails[1];
                var availableSeats = scheduleDetails[2];

                var scheduleHTML = `
                    <div class="form-check">
                        <input class="form-check-input selected-schedule" type="radio" name="selected_schedule" id="schedule${index}" value="${scheduleId}">
                        <label class="form-check-label" for="schedule${index}">
                            ${scheduleTime} (Cupos disponibles: ${availableSeats})
                        </label>
                    </div>`;
                schedulesContainer.append(scheduleHTML);
            });

            $('#reservationModal').modal('show');
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
