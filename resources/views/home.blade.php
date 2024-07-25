@extends('layouts.app_new')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Panel Principal') }}</div>
                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Acciones disponibles basadas en permisos del usuario -->
                    @canany(['create-user', 'edit-user', 'delete-user'])
                        <a class="btn btn-success btn-lg" href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> Gestionar usuarios</a>
                    @endcanany
                    @canany(['create-component', 'edit-component', 'delete-component'])
                        <a class="btn btn-warning btn-lg" href="{{ route('components.index') }}">
                            <i class="bi bi-house-gear"></i> Gestionar Componentes</a>
                    @endcanany
                    @canany(['create-tour', 'edit-tour', 'delete-tour'])
                        <a class="btn btn-secondary btn-lg" href="{{ route('tours.index') }}">
                            <i class="bi bi-bezier2"></i> Gestionar Tours</a>
                    @endcanany
                    <br>
                    <br>
                    @canany(['create-blog', 'edit-blog', 'delete-blog'])
                        <a class="btn btn-primary btn-lg" href="{{ route('blogs.index') }}">
                            <i class="bi bi-newspaper"></i> Gestionar Blogs</a>
                    @endcanany
                    @canany(['edit-donation', 'delete-donation'])
                        <a class="btn btn-success btn-lg" href="{{ route('donations.show') }}">
                            <i class="bi bi-coin"></i> Gestionar Donaciones</a>
                    @endcanany
                    @canany(['create-volunteer', 'edit-volunteer', 'delete-volunteer'])
                        <a class="btn btn-danger btn-lg" href="{{ route('volunteers.show') }}">
                            <i class="bi bi-wrench"></i> Voluntarios</a>
                    @endcanany

                    <br/><br/>
                    @if (Auth::user()->hasRole('Volunteer senior|Volunteer junior'))
                    <!-- Listado de tareas pendientes para el usuario actual -->
                    @if ($tasks->isNotEmpty())
                        <div class="alert alert-primary mt-4">
                            <strong>Mis Tareas Pendientes:</strong>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Titulo</th>
                                    <th scope="col">Contenido</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ ucfirst($task->type) }}</td>
                                        <td>{{ ucfirst($task->title) }}</td>
                                        <td>{{ ucfirst($task->content) }}</td>
                                        <td>{{ ucfirst($task->pivot->status) }}</td>
                                        <td>
                                            @if ($task->pivot->status == 'pending')
                                                <button type="button" class="btn btn-sm btn-success bi-check-lg complete-btn" data-id="{{ $task->id }}" data-toggle="tooltip" data-placement="top" title="Marcar como completada"></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No tienes tareas asignadas.</p>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.complete-btn').click(function(e) {
            e.preventDefault();
            var taskId = $(this).data('id');
            $.post('{{ url('/tasks') }}/' + taskId + '/complete', {
                _token: $('meta[name="csrf-token"]').attr('content'),
            }, function(response) {
                window.location.reload();
            }).fail(function(xhr) {
                console.log('Error al completar la tarea.');
            });
        });
    });
</script>
@endsection
