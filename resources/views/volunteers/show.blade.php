<link rel="stylesheet" href="{{ asset('css/volunteer.css') }}">

@extends('layouts.app_new')

@section('content')
<div class="card">
    <div class="card-header">Lista de Voluntarios</div>
    <div class="card-body">
    @canany(['create-task', 'edit-task', 'delete-task'])
        <a class="btn btn-warning btn-sm my-2" href="{{ route('tasks.index') }}">
        <i class="bi bi-journal"></i> Asignar Tareas</a>
    @endcanany
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
                    <th scope="col">Rol</th> <!-- Nueva columna para Rol -->
                    <th scope="col" style="width: 250px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($volunteers as $volunteer)
                <tr class="{{ $volunteer->status }}">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ ucfirst($volunteer->status) }}</td>
                    <td>{{ $volunteer->user->name }}</td>
                    <td>{{ $volunteer->user->dni }}</td>
                    <td>{{ $volunteer->user->email }}</td>
                    <td>{{ $volunteer->user->phone }}</td>
                    <td>{{ \Carbon\Carbon::parse($volunteer->user->birthdate)->format('d/m/Y') }}</td>
                    <td><a href="{{ asset('storage/'.$volunteer->cv_path) }}" download class="btn btn-sm btn-secondary"><i class="bi bi-download"></i></a></td>
                    <td>{{ $volunteer->additional_info }}</td>
                    <td>
                        @foreach ($volunteer->user->roles as $role)
                            <span class="role-badge">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if ($volunteer->status == 'pending')
                            <button type="button" class="btn btn-sm btn-success bi-check-lg approve-btn" data-id="{{ $volunteer->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Aprobar"></button>
                            <button type="button" class="btn btn-sm btn-danger bi-x-lg decline-btn" data-id="{{ $volunteer->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Rechazar"></button>
                        @endif
                        @if ($volunteer->status == 'active')
                            <button type="button" class="btn btn-sm btn-danger bi-x-lg decline-btn" data-id="{{ $volunteer->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Rechazar"></button>
                        @endif
                        @if ($volunteer->status == 'inactive')
                            <button type="button" class="btn btn-sm btn-success bi-check-lg approve-btn" data-id="{{ $volunteer->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Aprobar"></button>
                        @endif
                        <!-- Botón de eliminación -->
                        <form method="POST" action="{{ route('volunteers.destroy', $volunteer->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger bi-trash" onclick="return confirm('¿Está seguro de que desea eliminar esta solicitud de voluntariado?');" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"></button>
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="10"><span class="text-danger"><strong>No Volunteers Found!</strong></span></td>
                @endforelse
            </tbody>
        </table>
        {{ $volunteers->links() }}
    </div>
</div>

@include('volunteers.partials.volunteer_type_modal')
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('volunteerTypeModal');
    const volunteerTypeModal = new bootstrap.Modal(modalElement);
    const saveButton = document.getElementById('saveVolunteerType');

    // Manejo de botones de aprobación
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', function() {
            const volunteerId = this.getAttribute('data-id');
            modalElement.setAttribute('data-id', volunteerId);
            volunteerTypeModal.show();
        });
    });

    // Manejo de botones de declinación
    document.querySelectorAll('.decline-btn').forEach(button => {
        button.addEventListener('click', function() {
            const volunteerId = this.getAttribute('data-id');
            console.log('Attempting to decline volunteer with ID:', volunteerId);

            if (confirm('¿Estás seguro de que quieres rechazar a este voluntario?')) {
                fetch('{{ url("/volunteers") }}/' + volunteerId + '/decline', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: volunteerId })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Hecho',
                        text: 'Voluntario rechazado con éxito',
                    });
                    location.reload(); // Recargar la página para reflejar los cambios
                })
                .catch(error => {
                    console.error('Error al rechazar el voluntario:', error);
                    alert('Hubo un problema al rechazar al voluntario: ' + error.message);
                });
            }
        });
    });

    // Guardar tipo de voluntario
    saveButton.addEventListener('click', function() {
        const volunteerId = modalElement.getAttribute('data-id');
        const type = document.getElementById('volunteerType').value;
        volunteerTypeModal.hide();

        fetch('{{ url("/volunteers") }}/' + volunteerId + '/approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Hecho',
                text: 'Voluntario aprobado con éxito.',
            });
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al aprobar el voluntario.',
            });
        });
    });
});
</script>
@endsection
