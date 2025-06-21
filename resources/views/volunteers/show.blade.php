@extends('layouts.app_new')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/intranet/volunteer.css') }}">
@endsection 

@section('content')
<div class="intranet-main">

  <div class="card">
    <div class="card-header">Lista de Voluntarios</div>
    <div class="card-body">

      @canany(['create-task', 'edit-task', 'delete-task'])
        <a class="btn btn-warning btn-sm my-2" href="{{ route('tasks.index') }}">
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
              <th scope="col">Fecha Nacimiento</th>
              <th scope="col">CV</th>
              <th scope="col">Info</th>
              <th scope="col">Rol</th>
              <th scope="col" style="width:250px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              @php $vol = $user->volunteer; @endphp
              <tr class="{{ $vol->status ?? 'pending' }}">
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                  @if(!$vol)
                    Sin solicitud
                  @elseif($vol->status == 'pending')
                    Pendiente
                  @elseif($vol->status == 'active')
                    Activo
                  @else
                    Rechazado
                  @endif
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->dni }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->birthdate
                      ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y')
                      : '—' }}</td>
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
                <td>
                  @if(!$vol || $vol->status=='pending')
                    <button class="btn btn-sm btn-success approve-btn" data-id="{{ $user->id }}"
                            title="Aprobar"><i class="bi bi-check-lg"></i>
                    </button>
                    <button class="btn btn-sm btn-danger decline-btn" data-id="{{ $user->id }}"
                            title="Rechazar"><i class="bi bi-x-lg"></i>
                    </button>
                  @elseif($vol->status=='active')
                    <button class="btn btn-sm btn-danger decline-btn" data-id="{{ $user->id }}"
                            title="Rechazar"><i class="bi bi-x-lg"></i>
                    </button>
                  @elseif($vol->status=='inactive')
                    <button class="btn btn-sm btn-success approve-btn" data-id="{{ $user->id }}"
                            title="Aprobar"><i class="bi bi-check-lg"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="text-center text-danger">
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

  @include('volunteers.partials.volunteer_type_modal')

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const modalElement = document.getElementById('volunteerTypeModal');
  const volunteerTypeModal = new bootstrap.Modal(modalElement);
  const saveButton = document.getElementById('saveVolunteerType');

  // Aprobar
  document.querySelectorAll('.approve-btn').forEach(btn =>
    btn.addEventListener('click', () => {
      modalElement.setAttribute('data-id', btn.dataset.id);
      volunteerTypeModal.show();
    })
  );

  // Rechazar
  document.querySelectorAll('.decline-btn').forEach(btn =>
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      if (!confirm('¿Seguro quieres rechazar?')) return;
      fetch(`/volunteers/${id}/decline`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json'
        }
      })
      .then(r => r.json())
      .then(() => location.reload())
      .catch(e => alert('Error al rechazar: '+e));
    })
  );

  // Guardar tipo al aprobar
  saveButton.addEventListener('click', () => {
    const id   = modalElement.getAttribute('data-id');
    const type = document.getElementById('volunteerType').value;
    volunteerTypeModal.hide();
    fetch(`/volunteers/${id}/approve`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ type })
    })
    .then(r => r.json())
    .then(() => location.reload())
    .catch(e => Swal.fire('Error','No se pudo aprobar','error'));
  });
});
</script>
@endsection
