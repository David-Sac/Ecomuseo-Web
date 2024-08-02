@extends('layouts.app_new')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Información de la Visita
                </div>
                <div class="float-end">
                    <a href="{{ route('visits.index') }}" class="btn btn-primary btn-sm">&larr; Volver</a>
                </div>
            </div>
            <div class="card-body">

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Usuario:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $visit->user->name }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Tour:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $visit->tourSchedule->tour->name }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Número de Personas:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $visit->number_of_people }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Número de Teléfono:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        @php
                            $additionalInfo = json_decode($visit->additional_info, true);
                            echo $additionalInfo['contact_number'] ?? 'No se registró';
                        @endphp
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Acompañantes:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        @php
                            $companions = $additionalInfo['companions'] ?? [];
                            if (empty($companions)) {
                                echo 'No se registró';
                            } else {
                                foreach ($companions as $companion) {
                                    echo $companion['name'] . ' (' . $companion['age_group'] . '), ';
                                }
                            }
                        @endphp
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Fecha Seleccionada:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $visit->tourSchedule->day_of_week }} {{ date('g:i A', strtotime($visit->tourSchedule->start_time)) }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Estado:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ ucfirst($visit->status) }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Fecha de Solicitud:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $visit->requested_date }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Fecha de Aprobación:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $visit->approved_date ? $visit->approved_date : 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label text-md-end"><strong>Guía del Tour:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        @if ($visit->tourSchedule->tour->volunteers->isNotEmpty())
                            <p class="guide-info">
                                <strong>El guía para esta aventura será:</strong>
                                {{ $visit->tourSchedule->tour->volunteers->first()->name ?? 'No asignado' }}<br>
                                <strong>Su número de contacto es:</strong>
                                {{ $visit->tourSchedule->tour->volunteers->first()->phone ?? 'No disponible' }}
                            </p>
                        @else
                            <p class="guide-info">
                                <strong>El guía para esta aventura será:</strong> No asignado<br>
                                <strong>Su número de contacto es:</strong> No disponible
                            </p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
