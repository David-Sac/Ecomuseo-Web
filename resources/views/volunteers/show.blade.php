@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/volunteer.css') }}">
@endsection 

@section('content')
<div class="intranet-main">
  <div class="card">
    <div class="card-header">Lista de Voluntarios</div>
    <div class="card-body">
      @canany(['create-task','edit-task','delete-task'])
        <a class="btn btn-warning btn-sm mb-3" href="{{ route('tasks.index') }}">
          <i class="bi bi-journal"></i> Asignar Tareas
        </a>
      @endcanany

      <div class="table-container">
        <table id="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Estado</th>
              <th scope="col">Nombre</th>
              <th scope="col">DNI</th>
              <th scope="col">Email</th>
              <th scope="col">Fono</th>
              <th scope="col">Fecha Nac.</th>
              <th scope="col">CV</th>
              <th scope="col">Info</th>
              <th scope="col">Rol</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              @php $vol = $user->volunteer; @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  @if(!$vol)
                    Sin solicitud
                  @else
                    {{ ucfirst($vol->status) }}
                  @endif
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->dni }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>
                  {{ $user->birthdate
                     ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y')
                     : '—' }}
                </td>
                <td>
                  @if($vol)
                    <a href="{{ asset('storage/'.$vol->cv_path) }}" download
                       class="btn btn-sm btn-secondary">
                      <i class="bi bi-download"></i>
                    </a>
                  @endif
                </td>
                <td>{{ $vol->additional_info ?? '—' }}</td>
                <td>
                  @foreach($user->roles as $role)
                    <span class="role-badge">{{ $role->name }}</span>
                  @endforeach
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center text-danger">
                  ¡No se encontró ningún voluntario!
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $users->links() }}
    </div>
  </div>
</div>
@endsection

@section('scripts')
  {{-- Ya no hay JS de aprobar/rechazar aquí --}}
@endsection
