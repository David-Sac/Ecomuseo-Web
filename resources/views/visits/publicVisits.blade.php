@extends('layouts.app_new')

@section('styles')
  <!-- importa tu CSS de “Mis Visitas” -->
  <link rel="stylesheet" href="{{ asset('css/my_visits.css') }}">
@endsection

@section('content')
<main class="container" id="visist">
  <h2 class="text-center mb-4">Mis Visitas</h2>

  @foreach ($visits as $visit)
    <div class="table-container {{ $visit->status }}">
      <div class="visit-header">
        {{ $visit->tourSchedule->tour->name }}
      </div>
      <table class="table">
        <tbody>
          <tr class="visit-info">
            <td>Fecha de inicio del tour:</td>
            <td>{{ $visit->tourSchedule->tour->start_date }}</td>
          </tr>
          <tr class="visit-info">
            <td>Fecha de fin del tour:</td>
            <td>{{ $visit->tourSchedule->tour->end_date }}</td>
          </tr>
          <tr class="visit-info">
            <td>Número de cupos solicitados:</td>
            <td>{{ $visit->number_of_people }}</td>
          </tr>
          <tr class="visit-info">
            <td>Fecha solicitada para la visita:</td>
            <td>{{ $visit->requested_date }}</td>
          </tr>
          <tr class="visit-info">
            <td>Número de Teléfono:</td>
            <td>
              @php
                $info = json_decode($visit->additional_info, true) ?? [];
              @endphp
              {{ $info['contact_number'] ?? 'No se registró' }}
            </td>
          </tr>
          <tr class="visit-info">
            <td>Acompañantes:</td>
            <td>
              @php
                $companions = $info['companions'] ?? [];
              @endphp
              @if (empty($companions))
                No se registró
              @else
                @foreach ($companions as $c)
                  {{ $c['name'] }} ({{ $c['age_group'] }})@if (! $loop->last),@endif
                @endforeach
              @endif
            </td>
          </tr>
          <tr class="visit-info">
            <td>Estado de la visita:</td>
            <td>{{ ucfirst($visit->status) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  @endforeach
</main>
@endsection
