@extends('layouts.app_new')

@section('content')
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
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="tour" {{ old('type') == 'tour' ? 'selected' : '' }}>Tour</option>
                                <option value="blog" {{ old('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                                <option value="donation" {{ old('type') == 'donation' ? 'selected' : '' }}>Donaciones</option>
                                <option value="component" {{ old('type') == 'component' ? 'selected' : '' }}>Componente</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Selector de Voluntarios -->
                    <div class="mb-3 row">
                        <label for="volunteer_id" class="col-md-4 col-form-label text-md-end">Asignar a</label>
                        <div class="col-md-6">
                            <select class="form-control @error('volunteer_id') is-invalid @enderror" id="volunteer_id" name="volunteer_id">
                                @foreach ($volunteers as $volunteer)
                                    <option value="{{ $volunteer->id }}" {{ old('volunteer_id') == $volunteer->id ? 'selected' : '' }}>{{ $volunteer->name }}</option>
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
@endsection
