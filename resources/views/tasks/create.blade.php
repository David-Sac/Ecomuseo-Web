@extends('layouts.app_new')

@section('content')

<style>
    #componentChecklist {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Agregar Tarea</div>
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="post">
                    @csrf
                    <!-- Selector de tipo de tarea -->
                    <div class="mb-3 row">
                        <label for="type" class="col-md-4 col-form-label text-md-end">Tipo</label>
                        <div class="col-md-6">
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" onchange="filterVolunteers()">
                                <option value="">Seleccione tipo</option>
                                <option value="create-blog">Blog</option>
                                <option value="create-tour">Tour</option>
                                <option value="create-donation">Donaciones</option>
                                <option value="create-component">Mantenimiento de componentes</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Label para mostrar el tipo de voluntario requerido -->
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">Requerido para:</label>
                        <div class="col-md-6">
                            <p id="volunteerTypeRequired" class="form-control-plaintext"></p>
                        </div>
                    </div>

                    <!-- Checklist de Componentes -->
                    <div class="mb-3 row justify-content-center" id="componentChecklist" style="display: none;">
                        <label for="components" class="col-md-4 col-form-label text-md-end">Componentes</label>
                        <div class="col-md-6">
                            <div class="form-check">
                                @foreach ($components as $component)
                                    <div>
                                        <input type="checkbox" class="form-check-input" id="component-{{ $component->id }}" name="components[]" value="{{ $component->id }}">
                                        <label for="component-{{ $component->id }}" class="form-check-label">{{ $component->titleComponente }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Selector de Voluntarios -->
                    <div class="mb-3 row">
                        <label for="volunteer_id" class="col-md-4 col-form-label text-md-end">Asignar a</label>
                        <div class="col-md-6">
                            <select class="form-control @error('volunteer_id') is-invalid @enderror" id="volunteer_id" name="volunteer_id">
                                <option value="">Seleccione un voluntario</option>
                                @foreach ($volunteers as $volunteer)
                                    @php
                                        $permissions = $volunteer->roles->flatMap->permissions->pluck('name')->unique();
                                        $roles = $volunteer->roles->pluck('name');
                                    @endphp
                                    <option value="{{ $volunteer->id }}" data-permissions="{{ $permissions->join(',') }}" data-roles="{{ $roles->join(',') }}">
                                        {{ $volunteer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('volunteer_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para el título de la tarea -->
                    <div class="mb-3 row">
                        <label for="title" class="col-md-4 col-form-label text-md-end">Título</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Campo para el contenido de la tarea -->
                    <div class="mb-3 row">
                        <label for="content" class="col-md-4 col-form-label text-md-end">Contenido</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Asignar tarea">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function filterVolunteers() {
    const type = document.getElementById('type').value;
    const volunteers = document.getElementById('volunteer_id').options;
    let volunteersTypes = new Set();

    // Mostrar u ocultar el checklist de componentes
    const componentChecklist = document.getElementById('componentChecklist');
    componentChecklist.style.display = (type === 'create-component') ? 'flex' : 'none';

    // Deseleccionar todos los checkboxes de componentes si el tipo de tarea no es 'create-component'
    if (type !== 'create-component') {
        const checkboxes = document.querySelectorAll('#componentChecklist .form-check-input');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    for (const volunteer of volunteers) {
        if (volunteer.value === "") continue;

        const permissions = volunteer.getAttribute('data-permissions').split(',');
        const isVisible = permissions.includes(type);
        volunteer.style.display = isVisible ? 'block' : 'none';

        // Añadir roles al conjunto si el voluntario es visible
        if (isVisible) {
            const roles = volunteer.getAttribute('data-roles').split(',');
            roles.forEach(role => {
                if ((type === 'create-blog' || type === 'create-tour') && role.includes('Volunteer senior')) {
                    volunteersTypes.add('Volunteer senior');
                } else if ((type === 'create-donation' || type === 'create-component') && role.includes('Volunteer junior')) {
                    volunteersTypes.add('Volunteer junior');
                }
            });
        }
    }

    // Actualizar el texto de los roles requeridos
    document.getElementById('volunteerTypeRequired').textContent = volunteersTypes.size > 0 ? Array.from(volunteersTypes).join(', ') : 'No hay voluntarios disponibles para este tipo de tarea';
}

document.getElementById('type').addEventListener('change', filterVolunteers);
</script>

@endsection
