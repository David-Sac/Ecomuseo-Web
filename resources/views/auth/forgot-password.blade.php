<!DOCTYPE html>
<html lang="es">

<link rel="stylesheet" href="{{ asset('css/login_style.css') }}">
<link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_vectorizado.svg') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña - Ecomuseo LLAQTA AMARU - YOYEN KUWA</title>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="top-left-div">
                Restablecer Contraseña
            </div>
        </div>
        <div class="right-side">
            <div class="login-form">
                <img src="{{ asset('images/logo_vectorizado.svg') }}" alt="Logo" class="logo-svg">

                <h3>RESTABLECER CONTRASEÑA</h3>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('¿Olvidaste tu contraseña? Ingrese su correo electrónico y le enviaremos un enlace para restablecer su contraseña.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Correo electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Ingresa su correo electrónico..."/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="login-section">
                        <x-primary-button>
                            {{ __('Aceptar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
