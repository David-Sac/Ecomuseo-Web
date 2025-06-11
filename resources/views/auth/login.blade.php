<!DOCTYPE html>
<html lang="es">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/login_style.css') }}">
<link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_vectorizado.svg') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión - Ecomuseo LLAQTA AMARU -YOYEN KUWA</title>
</head>
<body>
    <header>
        @include('partials.header_new')
    </header>

    <div class="login-container">
        <div class="login-card">
        <div class="left-side">
            <div class="carousel"></div>
        </div>
        <div class="right-side">
            <div class="login-form">
                

                {{-- <h2>Bienvenido a ECOMUSEO</h2> --}}
                <h3>INICIAR SESIÓN</h3>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Correo electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username"
                        placeholder="Ingresa su correo electrónico..."/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Contraseña')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password"
                                        placeholder="Ingresa su contraseña..."/>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="div-remenber-me">
                        <label for="remember_me" class="label items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Recuerdame la contraseña') }}</span>
                        </label>
                    </div>


                    <div class="login-section">


                        <x-primary-button class="btn-login">
                            Ingresar
                        </x-primary-button>

                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                {{ __('¿Olvidadaste la contraseña?') }}
                            </a>
                        @endif
                    </div>
                    <a href="/google-auth/redirect" id="link-google">
                        <img src="{{ asset('images/google_logo_2.svg') }}" alt="Iniciar sesión por google" class="google_logo_svg">
                    </a>
                </form>
            </div>
        </div>
    </div>
    </div>


</body>
</html>
