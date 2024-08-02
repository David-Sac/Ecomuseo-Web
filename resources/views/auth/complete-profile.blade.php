<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_vectorizado.svg') }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
        <script src="{{ asset('js/welcome.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

        <title>Completa tu perfil - Ecomuseo LLAQTA AMARU - YOREN KUWAI</title>

        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            main {
                flex: 1;
            }
            .form-group label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
                border: 1px solid #ced4da;
                padding: 5px;
                border-radius: 4px;
            }
            .form-group input {
                border-radius: 4px;
            }
        </style>
    </head>

    <body>
        <header>
            @include('partials.header_new')
        </header>

        <main class="main-content" style="margin-top: 150px;">
            <div class="container mt-5">
                <h2 class="mb-4">Complete su perfil para continuar</h2>
                <form method="POST" action="{{ route('complete-profile.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni" name="dni" value="{{ old('dni') }}" required>
                        @error('dni')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Fecha de Nacimiento</label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                        @error('birthdate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </main>
        <footer>
            @include('partials.footer')
        </footer>
    </body>
</html>
