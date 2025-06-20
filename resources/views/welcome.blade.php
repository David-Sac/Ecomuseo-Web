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

        <title>Ecomuseo LLAQTA AMARU - YOREN KUWAI</title>
    </head>

    <body>
        @extends('layouts.app_new')

        <main class="main-content">
            <!-- Banner de Imagen -->
            <div class="full-screen-banner">
                <h1>Bienvenidos al Ecomuseo</h1>
            </div>

            <!-- Componentes -->
            <section class="actividades" id="actividades">
                <h1 class="titulo"><span>Componentes</span></h1>
                <p class="subtitle">
                Descubre los recursos naturales que conservamos y tu papel como visitante.
                </p>
                <ul class="components-benefits">
                <li>Aprende sobre la abeja melipona</li>
                <li>Contribuye a la reforestación</li>
                <li>Participa en talleres interactivos</li>
                </ul>

                <div id="carouselComponentes" class="carousel slide" data-ride="carousel"data-interval="false">
                    <div class="carousel-inner">
                        @foreach ($components->chunk(3) as $chunkIndex => $chunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center gap-4">
                                @foreach ($chunk as $component)
                                <div class="card mx-2 component-card">
                                    <div class="image-container">
                                        <img src="{{ asset($component->rutaImagenComponente) }}"
                                            alt="{{ $component->titleComponente }}">
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between text-center">
                                        <h5 class="card-title">{{ $component->titleComponente }}</h5>
                                        <p class="card-text">{{ $component->description }}</p>
                                        <a href="{{ route('components.publicShow', $component->id) }}"
                                        class="btn btn-dark mt-auto">Más información</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Controles visibles -->
                    <a class="carousel-control-prev" href="#carouselComponentes" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselComponentes" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                </div>
                <div class="testimonials">
                <blockquote>“Una experiencia inolvidable en plena Amazonía.” – María G.</blockquote>
                </div>
            </section>

                
                <div class="encuentranos-container">
                    <div class="info">
                        <h2>Encuéntranos en...</h2>
                            <div class="item">
                                <!-- Icono de ubicación (puedes usar tu SVG o FontAwesome) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                                <p><strong>Ubicación:</strong> Junto a la Facultad de Agronomía de la UNAP, Av. Simón Bolívar s/n, Iquitos, Perú.</p>
                            </div>

                            <div class="item">
                                <!-- Icono de reloj -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 1010 10A10 10 0 0012 2zm1 11h4v-2h-6V5h2z"/>
                                </svg>
                                <p><strong>Horario:</strong> Lunes a viernes de 9:00 a.m. a 5:00 p.m.</p>
                            </div>

                            <div class="item">
                                <!-- Icono de teléfono -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24">
                                    <path d="M17.707 12.293l-2.414-2.414a1 1 0 00-1.414 1.414l.293.293a11.036 11.036 0 01-5.657 5.657l-.293-.293a1 1 0 10-1.414 1.414l2.414 2.414a1 1 0 001.414 0 13.036 13.036 0 006.364-6.364 1 1 0 000-1.414z"/>
                                </svg>
                                <p><strong>Contacto:</strong> +51 992 585 999</p>
                            </div>
                    </div>
                    <div class="mapa">
                                <!-- Esto lo obtienes de Google Maps > Compartir > Incrustar -->
                                <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1690.9858055164493!2d-73.3698942934998!3d-3.833691532402468!3m2!1i1024!2i768!4f13.1!5e1!3m2!1ses-419!2spe!4v1748388320480!5m2!1ses-419!2spe" 
                                width="800" 
                                height="600" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <!-- ↓↓↓ CARRUSEL DINÁMICO DE COMPONENTES ↓↓↓ -->
                <div class="contenedor">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    
                    {{-- 1) Indicadores --}}
                    <ol class="carousel-indicators">
                        @foreach($components as $i => $component)
                        <li data-target="#carouselExampleIndicators"
                            data-slide-to="{{ $i }}"
                            class="{{ $i === 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    
                    {{-- 2) Slides --}}
                    <div class="carousel-inner">
                        @foreach($components as $i => $component)
                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                            <img class="d-block w-100"
                                src="{{ asset($component->rutaImagenComponente) }}"
                                alt="{{ $component->titleComponente }}">
                            <div class="carousel-caption d-none d-md-block">
                            <h5>{{ $component->titleComponente }}</h5>
                            <p>{{ $component->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- 3) Controles --}}
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                    </div>
                </div>
            </div>

        </main>
    </body>
</html>

