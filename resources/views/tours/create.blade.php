@extends('layouts.app_new')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Añadir Tour</div>
            <div class="card-body">
                <form action="{{ route('tours.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Campo para el nombre del tour -->
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre del Tour</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para la descripción del tour -->
                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Descripción</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para la fecha de inicio del tour -->
                    <div class="mb-3 row">
                        <label for="start_date" class="col-md-4 col-form-label text-md-end text-start">Fecha de Inicio</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para la fecha de fin del tour -->
                    <div class="mb-3 row">
                        <label for="end_date" class="col-md-4 col-form-label text-md-end text-start">Fecha de Fin</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para la información de contacto del tour -->
                    <div class="mb-3 row">
                        <label for="contact_info" class="col-md-4 col-form-label text-md-end text-start">Información de Contacto</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('contact_info') is-invalid @enderror" id="contact_info" name="contact_info" value="{{ old('contact_info') }}" placeholder="Email, phone number, etc.">
                            @error('contact_info')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Lista de componentes para marcar -->
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end text-start">Componentes Involucrados</label>
                        <div class="col-md-6">
                            @foreach ($components as $component)
                                <div class="form-check">
                                    <input class="form-check-input @error('components') is-invalid @enderror" type="checkbox" value="{{ $component->id }}" id="component{{ $component->id }}" name="components[]" {{ in_array($component->id, old('components', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="component{{ $component->id }}">
                                        {{ $component->titleComponente }}
                                    </label>
                                </div>
                            @endforeach
                            @error('components')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Lista de voluntarios para seleccionar -->
                    <div class="mb-3 row">
                        <label for="volunteer_id" class="col-md-4 col-form-label text-md-end text-start">Asignar Voluntario</label>
                        <div class="col-md-6">
                            <select class="form-control @error('volunteer_id') is-invalid @enderror" id="volunteer_id" name="volunteer_id">
                                @foreach ($volunteers as $volunteer)
                                    <option value="{{ $volunteer->id }}" {{ old('volunteer_id') == $volunteer->id ? 'selected' : '' }}>
                                        {{ $volunteer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('volunteer_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para el periodo de visibilidad -->
                    <div class="mb-3 row">
                        <label for="visibility_period" class="col-md-4 col-form-label text-md-end">Periodo de Visibilidad</label>
                        <div class="col-md-6">
                            <select class="form-control @error('visibility_period') is-invalid @enderror" id="visibility_period" name="visibility_period">
                                <option value="1 día" {{ old('visibility_period') == '1 día' ? 'selected' : '' }}>1 día</option>
                                <option value="2 días" {{ old('visibility_period') == '2 días' ? 'selected' : '' }}>2 días</option>
                                <option value="1 semana" {{ old('visibility_period') == '1 semana' ? 'selected' : '' }}>1 semana</option>
                                <option value="2 semanas" {{ old('visibility_period') == '2 semanas' ? 'selected' : '' }}>2 semanas</option>
                                <option value="1 mes" {{ old('visibility_period') == '1 mes' ? 'selected' : '' }}>1 mes</option>
                                <option value="2 meses" {{ old('visibility_period') == '2 meses' ? 'selected' : '' }}>2 meses</option>
                                <option value="3 meses" {{ old('visibility_period') == '3 meses' ? 'selected' : '' }}>3 meses</option>
                            </select>
                            @error('visibility_period')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para los horarios del tour -->
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end text-start">Horarios del Tour</label>
                        <div class="col-md-6">
                            <div id="schedules-container">
                                @if(old('schedules'))
                                    @foreach(old('schedules') as $index => $schedule)
                                        <div class="schedule-group">
                                            <div class="form-row">
                                                <div class="col">
                                                    <select name="schedules[{{ $index }}][day_of_week]" class="form-control @error('schedules.*.day_of_week') is-invalid @enderror">
                                                        <option value="Lunes" {{ $schedule['day_of_week'] == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                                        <option value="Martes" {{ $schedule['day_of_week'] == 'Martes' ? 'selected' : '' }}>Martes</option>
                                                        <option value="Miércoles" {{ $schedule['day_of_week'] == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                                        <option value="Jueves" {{ $schedule['day_of_week'] == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                                        <option value="Viernes" {{ $schedule['day_of_week'] == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                                        <option value="Sábado" {{ $schedule['day_of_week'] == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                                                        <option value="Domingo" {{ $schedule['day_of_week'] == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                                                    </select>
                                                    @error('schedules.*.day_of_week')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <input type="time" name="schedules[{{ $index }}][start_time]" class="form-control @error('schedules.*.start_time') is-invalid @enderror" value="{{ $schedule['start_time'] }}">
                                                    @error('schedules.*.start_time')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <input type="time" name="schedules[{{ $index }}][end_time]" class="form-control @error('schedules.*.end_time') is-invalid @enderror" value="{{ $schedule['end_time'] }}">
                                                    @error('schedules.*.end_time')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <input type="number" name="schedules[{{ $index }}][max_capacity]" class="form-control @error('schedules.*.max_capacity') is-invalid @enderror" placeholder="Capacidad Máxima" value="{{ $schedule['max_capacity'] }}">
                                                    @error('schedules.*.max_capacity')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-danger remove-schedule">-</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="schedule-group">
                                        <div class="form-row">
                                            <div class="col">
                                                <select name="schedules[0][day_of_week]" class="form-control">
                                                    <option value="Lunes">Lunes</option>
                                                    <option value="Martes">Martes</option>
                                                    <option value="Miércoles">Miércoles</option>
                                                    <option value="Jueves">Jueves</option>
                                                    <option value="Viernes">Viernes</option>
                                                    <option value="Sábado">Sábado</option>
                                                    <option value="Domingo">Domingo</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input type="time" name="schedules[0][start_time]" class="form-control">
                                            </div>
                                            <div class="col">
                                                <input type="time" name="schedules[0][end_time]" class="form-control">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="schedules[0][max_capacity]" class="form-control" placeholder="Capacidad Máxima">
                                            </div>
                                            <div class="col">
                                                <button type="button" class="btn btn-danger remove-schedule">-</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="add-schedule">Agregar Horario</button>
                        </div>
                    </div>

                    @error('schedules')
                        <div class="text-danger text-center">{{ $message }}</div>
                    @enderror

                    <!-- Botón de envío -->
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Añadir Tour">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let scheduleIndex = {{ count(old('schedules', [0])) }};

        document.getElementById('add-schedule').addEventListener('click', function () {
            const container = document.getElementById('schedules-container');
            const newSchedule = document.createElement('div');
            newSchedule.classList.add('schedule-group');
            newSchedule.innerHTML = `
                <div class="form-row">
                    <div class="col">
                        <select name="schedules[${scheduleIndex}][day_of_week]" class="form-control">
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="time" name="schedules[${scheduleIndex}][start_time]" class="form-control">
                    </div>
                    <div class="col">
                        <input type="time" name="schedules[${scheduleIndex}][end_time]" class="form-control">
                    </div>
                    <div class="col">
                        <input type="number" name="schedules[${scheduleIndex}][max_capacity]" class="form-control" placeholder="Capacidad Máxima">
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-danger remove-schedule">-</button>
                    </div>
                </div>
            `;
            container.appendChild(newSchedule);
            scheduleIndex++;
        });

        document.addEventListener('click', function (event) {
            if (event.target && event.target.matches('.remove-schedule')) {
                event.target.closest('.schedule-group').remove();
            }
        });
    });
</script>
@endsection
