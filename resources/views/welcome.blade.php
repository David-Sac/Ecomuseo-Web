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
        <header>
            @include('partials.header_new')
        </header>

        <main class="main-content">
            <!-- Banner de Imagen -->
            <div class="full-screen-banner">
                <h1>Bienvenidos al Eco Museo</h1>
                <p>Descubre la riqueza de nuestra cultura</p>
            </div>

            <!-- Componentes -->
            <section class="actividades" id="actividades">
                <h1 class="titulo"><span>Componentes</span></h1>
                <div class="box-container">
                    @foreach ($components as $component)
                    <div class="box">
                        <div class="actividad">
                            <img src="{{ asset($component->rutaImagenComponente) }}" alt="Foto de {{ $component->titleComponente }}">
                            <h3>{{ $component->titleComponente }}</h3>
                            <p>{{ $component->description }}</p>
                            <a href="{{ route('components.publicShow', $component->id) }}">Más información</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

                <div class="contenedor">
                <!-- Carousel de Imágenes -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="../images/welcome/carrousel_1.jpg" alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="../images/welcome/Photo_Agro.jpg" alt="Second slide">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>

                <div class="encuentranos-container">
                    <h2>Encuéntranos en...</h2>
                    <div class="mapa">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4809.269451116934!2d-73.37070887429928!3d-3.8341447434190625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2spe!4v1706464087717!5m2!1ses-419!2spe" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>


        </main>
        <footer>
            @include('partials.footer')
        </footer>
    </body>
</html>

