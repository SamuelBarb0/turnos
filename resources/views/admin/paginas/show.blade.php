<!-- resources/views/paginas/show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pagina->title }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        section {
            padding: 80px 0;
        }
        
        .section-with-bg {
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            color: #fff;
            position: relative;
        }
        
        .section-with-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        
        .section-with-bg .container {
            position: relative;
            z-index: 2;
        }
        
        .btn-primary {
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .hero-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        
        .feature-card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .pricing-card {
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #eee;
            height: 100%;
        }
        
        .pricing-card:hover {
            transform: scale(1.03);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .pricing-card .price {
            font-size: 42px;
            font-weight: 700;
            margin: 20px 0;
        }
        
        .footer {
            background: #222;
            color: #fff;
            padding: 60px 0 30px;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: #fff;
            text-decoration: none;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <!-- Logo o nombre de la aplicación -->
                <strong>Tu App de Turnos</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Inicio</a>
                    </li>
                    <!-- Agregar más enlaces según sea necesario -->
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Panel</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Contenido dinámico de la página -->
@foreach($pagina->secciones()->orderBy('orden')->get() as $seccion)
    @php
        $bgStyle = $seccion->ruta_image ? "background-image: url('" . asset('storage/' . $seccion->ruta_image) . "');" : "";
        $hasBg = $seccion->ruta_image ? 'section-with-bg' : '';
    @endphp
    
    <section id="seccion-{{ $seccion->id }}" class="{{ $hasBg }}" style="{{ $bgStyle }}">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @foreach($seccion->contenidos()->orderBy('orden')->get() as $contenido)
                        @switch($contenido->etiqueta)
                            @case('h1')
                                <h1 class="display-4 mb-4">{{ $contenido->contenido }}</h1>
                                @break
                            @case('h2')
                                <h2 class="mb-4">{{ $contenido->contenido }}</h2>
                                @break
                            @case('h3')
                                <h3 class="mb-3">{{ $contenido->contenido }}</h3>
                                @break
                            @case('p')
                                <p class="lead mb-4">{{ $contenido->contenido }}</p>
                                @break
                            @case('button')
                                @php
                                    $buttonParts = explode('|', $contenido->contenido);
                                    $buttonText = $buttonParts[0] ?? 'Click aquí';
                                    $buttonClass = $buttonParts[1] ?? 'btn-primary';
                                    $buttonUrl = $buttonParts[2] ?? '#';
                                @endphp
                                <a href="{{ $buttonUrl }}" class="btn {{ $buttonClass }} mb-4">{{ $buttonText }}</a>
                                @break
                            @case('a')
                                @php
                                    $linkParts = explode('|', $contenido->contenido);
                                    $linkText = $linkParts[0] ?? 'Enlace';
                                    $linkUrl = $linkParts[1] ?? '#';
                                @endphp
                                <a href="{{ $linkUrl }}" class="mb-4 d-inline-block">{{ $linkText }}</a>
                                @break
                            @case('img')
                                <img src="{{ $contenido->contenido }}" alt="Imagen" class="img-fluid mb-4">
                                @break
                            @case('ul')
                                <ul class="mb-4">
                                    @foreach(explode("\n", $contenido->contenido) as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                                @break
                            @default
                                <div class="mb-4">{{ $contenido->contenido }}</div>
                        @endswitch
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endforeach

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4>Tu App de Turnos</h4>
                <p>Simplificando la gestión de turnos y citas para profesionales y empresas.</p>
            </div>
            <div class="col-md-4">
                <h4>Enlaces Útiles</h4>
                <ul class="list-unstyled">
                    <li><a href="/">Inicio</a></li>
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li><a href="{{ route('register') }}">Registrarse</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4>Contacto</h4>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2"></i> info@tuapp.com</li>
                    <li><i class="fas fa-phone me-2"></i> +1234567890</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Calle Principal 123, Ciudad</li>
                </ul>
            </div>
        </div>
        <div class="row footer-bottom">
            <div class="col-md-12 text-center">
                <p>&copy; {{ date('Y') }} Tu App de Turnos. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>