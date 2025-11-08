<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galería - La Chacra Gourmet</title>
  <link rel="stylesheet" href="estilos/estilo_general.css?v=<?php echo time(); ?>">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="contenedor-principal">
    <!-- Header -->
    <header class="menu">
      <div class="logo-container">
        <div class="logo">
          <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
        </div>
        <!-- Selector de idioma personalizado -->
        <div class="language-selector">
          <button class="btn-language" onclick="toggleLanguageMenu()">
            <i class="fas fa-globe me-1"></i>
            <span id="current-language">ES</span>
            <i class="fas fa-chevron-down ms-1"></i>
          </button>
          <div class="language-menu" id="languageMenu">
            <button onclick="changeLanguage('es', 'ES')">
              <i class="flag-icon">🇪🇸</i> Español
            </button>
            <button onclick="changeLanguage('en', 'EN')">
              <i class="flag-icon">🇺🇸</i> English
            </button>
            <button onclick="changeLanguage('pt', 'PT')">
              <i class="flag-icon">🇧🇷</i> Português
            </button>
          </div>
        </div>
      </div>
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <?php if (isset($_SESSION['es_empleado']) && $_SESSION['es_empleado'] === true): ?>
            <li><a href="zona_staff.php">Mozos orden</a></li>
          <?php endif; ?>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="galeria.php">Galería</a></li>
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
          <?php if (isset($_SESSION['id_usuario'])): ?>
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>)</a></li>
          <?php else: ?>
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar sesión</a></li>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- El resto de tu contenido se mantiene igual -->
    <main class="contenido-principal">
      <section class="banner-admin">
        <h1>Galería de Nuestro Local</h1>
      </section>

      <!-- Carrusel Principal -->
      <section class="seccion-admin">
        <div class="container-fluid px-0">
          <div id="carouselPrincipal" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="0" class="active"></button>
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="1"></button>
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="2"></button>
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="estilos/imagenes/images (2).jpg" class="d-block w-100 carousel-img" alt="Ambiente principal del restaurante">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Ambiente Principal</h5>
                  <p>Nuestro espacioso comedor con decoración tradicional</p>
                </div>
              </div>
              <div class="carousel-item">
                <img src="estilos/imagenes/images (3).jpg" class="d-block w-100 carousel-img" alt="Zona de parrilla">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Parrilla</h5>
                  <p>En donde las mejores comidas se hacen</p>
                </div>
              </div>
              <div class="carousel-item">
                <img src="estilos/imagenes/b17588_76cd0d7eeb1b4782a961e94246de391a~mv2.avif" class="d-block w-100 carousel-img" alt="Jardín al aire libre">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Jardín al aire libre</h5>
                  <p>Disfruta nuestro espacio al aire libre</p>
                </div>
              </div>
              <div class="carousel-item">
                <img src="estilos/imagenes/descarga (4).jpg" class="d-block w-100 carousel-img" alt="Ambiente familiar">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Ambiente</h5>
                  <p>Ideal para pasar tiempo en familia o con amigos</p>
                </div>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPrincipal" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselPrincipal" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Siguiente</span>
            </button>
          </div>
        </div>
      </section>

      <!-- Grid de Imágenes con Bootstrap -->
      <section class="seccion-admin">
        <div class="container">
          <h2 class="text-center mb-5">Nuestros Espacios</h2>
          <div class="row g-4">
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga (2).jpg" class="card-img-top" alt="Huerta orgánica">
                <div class="card-body">
                  <h5 class="card-title">Huerta</h5>
                  <p class="card-text">Nos aseguramos que nuestros ingredientes sean los más naturales y frescos posibles.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga (3).jpg" class="card-img-top" alt="Equipo de chefs">
                <div class="card-body">
                  <h5 class="card-title">Nuestros Chefs</h5>
                  <p class="card-text">Profesionales apasionados creando experiencias culinarias inolvidables.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga.jpg" class="card-img-top" alt="Patio infantil">
                <div class="card-body">
                  <h5 class="card-title">Patio para los niños</h5>
                  <p class="card-text">Espacio para que los padres puedan dejar que sus niños se diviertan.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga (1).jpg" class="card-img-top" alt="Rincón especial">
                <div class="card-body">
                  <h5 class="card-title">Detalles Únicos</h5>
                  <p class="card-text">Rincón para celebrar fechas especiales.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Información Adicional -->
      <section class="seccion-admin">
        <div class="container">
          <div class="row align-items-stretch">
            <div class="col-md-6 d-flex flex-column justify-content-center">
              <div class="pe-md-4">
                <h3>Visita Nuestro Local</h3>
                <p class="lead">Te invitamos a descubrir por qué La Chacra Gourmet es más que un restaurante, es una experiencia.</p>
                <ul class="list-unstyled mb-4">
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Ambiente climatizado</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Estacionamiento gratuito</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Acceso para personas con movilidad reducida</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Wi-Fi gratuito</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Patio con juegos infantiles</li>
                </ul>
                <div class="mt-auto">
                  <a href="reservas1.php" class="btn btn-primary me-3">Reservar Mesa</a>
                  <a href="menu.php" class="btn btn-outline-primary">Ver Menú</a>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="image-container h-100">
                <img src="estilos/imagenes/images (1).jpg" alt="Vista del restaurante La Chacra Gourmet" 
                     class="img-fluid rounded shadow w-100 h-100 object-fit-cover">
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - GALERÍA</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="reservas1.php" class="btn-enlace">Hacer Reserva</a>
      </div>
    </footer>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Script para el selector de idioma -->
  <script>
  function toggleLanguageMenu() {
      const menu = document.getElementById('languageMenu');
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  }

  function changeLanguage(lang, langCode) {
      // Cambiar el idioma mostrado
      document.getElementById('current-language').textContent = langCode;
      
      // Aquí puedes agregar la lógica para cambiar el idioma del sitio
      // Por ahora, solo cerramos el menú
      document.getElementById('languageMenu').style.display = 'none';
      
      // Mostrar mensaje de que la función está en desarrollo
      alert('Función de traducción en desarrollo. Próximamente disponible.');
  }

  // Cerrar el menú si se hace clic fuera de él
  document.addEventListener('click', function(event) {
      const languageSelector = document.querySelector('.language-selector');
      const languageMenu = document.getElementById('languageMenu');
      if (!languageSelector.contains(event.target)) {
          languageMenu.style.display = 'none';
      }
  });
  </script>
  
  <style>
    .carousel-img {
      height: 600px;
      object-fit: cover;
    }

    .galeria-card {
      border: 2px solid #C98A5E;
      border-radius: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .galeria-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(201, 138, 94, 0.3);
    }

    .galeria-card .card-img-top {
      height: 250px;
      object-fit: cover;
      border-radius: 8px 8px 0 0;
    }

    .carousel-caption {
      background: rgba(91, 58, 41, 0.8);
      border-radius: 10px;
      padding: 20px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: #C98A5E;
      border-radius: 50%;
      padding: 15px;
    }

    .carousel-indicators button {
      background-color: #C98A5E;
    }

    .btn-primary {
      background-color: #C98A5E;
      border-color: #C98A5E;
    }

    .btn-primary:hover {
      background-color: #B07A4E;
      border-color: #B07A4E;
    }

    .btn-outline-primary {
      color: #C98A5E;
      border-color: #C98A5E;
    }

    .btn-outline-primary:hover {
      background-color: #C98A5E;
      border-color: #C98A5E;
      color: white;
    }

    /* ESTILOS MEJORADOS PARA EL SELECTOR DE IDIOMA - CORREGIDOS */
    .logo-container {
      display: flex;
      align-items: center;
      gap: 20px;
      position: relative;
    }

    .language-selector {
      position: relative;
      display: inline-block;
    }

    .btn-language {
      background: white;
      border: 1px solid #C98A5E;
      border-radius: 20px;
      padding: 8px 15px;
      color: #5B3A29;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      z-index: 1001;
      position: relative;
    }

    .btn-language:hover {
      background: rgba(201, 138, 94, 0.1);
      transform: translateY(-1px);
    }

    .language-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background: white;
      border: 1px solid #C98A5E;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      min-width: 150px;
      z-index: 1002; /* Z-INDEX MÁS ALTO PARA QUE SE VEA POR ENCIMA */
      margin-top: 5px;
    }

    .language-menu button {
      width: 100%;
      background: none;
      border: none;
      padding: 10px 15px;
      text-align: left;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #5B3A29;
      transition: background 0.3s ease;
      z-index: 1003;
    }

    .language-menu button:hover {
      background: rgba(201, 138, 94, 0.1);
    }

    .language-menu button:first-child {
      border-radius: 8px 8px 0 0;
    }

    .language-menu button:last-child {
      border-radius: 0 0 8px 8px;
    }

    .flag-icon {
      font-size: 16px;
    }

    /* IMPORTANTE: Asegurar que el header tenga un z-index alto */
    .menu {
      position: relative;
      z-index: 1000; /* Z-index alto para el header */
    }

    /* Asegurar que el contenido principal no interfiera */
    .contenido-principal {
      position: relative;
      z-index: 1; /* Z-index bajo para el contenido */
    }

    /* Estilos mejorados para la sección de imagen */
    .image-container {
      min-height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .object-fit-cover {
      object-fit: cover;
    }

    @media (max-width: 768px) {
      .carousel-img {
        height: 400px;
      }
      
      .carousel-caption h5 {
        font-size: 1rem;
      }
      
      .carousel-caption p {
        font-size: 0.8rem;
      }

      .logo-container {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
      }

      .language-selector {
        align-self: flex-start;
      }

      .image-container {
        min-height: 300px;
        margin-top: 2rem;
      }

      /* En móviles, el menú se abre hacia la derecha */
      .language-menu {
        left: auto;
        right: 0;
      }
    }

    @media (max-width: 576px) {
      .carousel-img {
        height: 300px;
      }

      .logo-container {
        align-items: center;
        text-align: center;
      }

      .language-selector {
        align-self: center;
      }

      .btn-language {
        padding: 6px 12px;
        font-size: 12px;
      }
    }
  </style>
</body>
</html>