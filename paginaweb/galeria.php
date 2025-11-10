```php
<?php
// Iniciar sesión para acceder a las variables de sesión
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galería - La Chacra Gourmet</title>
  <!-- Hoja de estilos personalizada con parámetro de versión para evitar cache -->
  <link rel="stylesheet" href="estilos/estilo_general.css?v=<?php echo time(); ?>">
  <!-- Bootstrap CSS para componentes responsivos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome para iconos -->
  <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="contenedor-principal">
    <!-- Header de navegación principal -->
    <header class="menu">
      <div class="logo-container">
        <div class="logo">
          <!-- Logo del restaurante -->
          <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
        </div>
      </div>
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <!-- Mostrar enlace para empleados solo si el usuario tiene ese rol -->
          <?php if (isset($_SESSION['es_empleado']) && $_SESSION['es_empleado'] === true): ?>
            <li><a href="zona_staff.php">Mozos orden</a></li>
          <?php endif; ?>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="galeria.php">Galería</a></li>
          <!-- Mostrar enlace para administradores solo si el usuario tiene ese rol -->
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
          <!-- Mostrar opciones de usuario según estado de sesión -->
          <?php if (isset($_SESSION['id_usuario'])): ?>
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>)</a></li>
          <?php else: ?>
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar sesión</a></li>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenido Principal de la Galería -->
    <main class="contenido-principal">
      <!-- Banner principal de la galería -->
      <section class="banner-admin">
        <h1>Galería de Nuestro Local</h1>
      </section>

      <!-- Carrusel Principal con imágenes del local -->
      <section class="seccion-admin">
        <div class="container-fluid px-0">
          <!-- Carrusel Bootstrap con controles e indicadores -->
          <div id="carouselPrincipal" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicadores del carrusel (puntos de navegación) -->
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="0" class="active"></button>
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="1"></button>
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="2"></button>
              <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="3"></button>
            </div>
            <!-- Contenedor de los elementos del carrusel -->
            <div class="carousel-inner">
              <!-- Primer elemento del carrusel (activo por defecto) -->
              <div class="carousel-item active">
                <img src="estilos/imagenes/images (2).jpg" class="d-block w-100 carousel-img" alt="Ambiente principal del restaurante">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Ambiente Principal</h5>
                  <p>Nuestro espacioso comedor con decoración tradicional</p>
                </div>
              </div>
              <!-- Segundo elemento del carrusel -->
              <div class="carousel-item">
                <img src="estilos/imagenes/images (3).jpg" class="d-block w-100 carousel-img" alt="Zona de parrilla">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Parrilla</h5>
                  <p>En donde las mejores comidas se hacen</p>
                </div>
              </div>
              <!-- Tercer elemento del carrusel -->
              <div class="carousel-item">
                <img src="estilos/imagenes/b17588_76cd0d7eeb1b4782a961e94246de391a~mv2.avif" class="d-block w-100 carousel-img" alt="Jardín al aire libre">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Jardín al aire libre</h5>
                  <p>Disfruta nuestro espacio al aire libre</p>
                </div>
              </div>
              <!-- Cuarto elemento del carrusel -->
              <div class="carousel-item">
                <img src="estilos/imagenes/descarga (4).jpg" class="d-block w-100 carousel-img" alt="Ambiente familiar">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Ambiente</h5>
                  <p>Ideal para pasar tiempo en familia o con amigos</p>
                </div>
              </div>
            </div>
            <!-- Controles de navegación del carrusel -->
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

      <!-- Grid de Imágenes con Bootstrap para mostrar diferentes espacios -->
      <section class="seccion-admin">
        <div class="container">
          <h2 class="text-center mb-5">Nuestros Espacios</h2>
          <div class="row g-4">
            <!-- Tarjeta 1: Huerta orgánica -->
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga (2).jpg" class="card-img-top" alt="Huerta orgánica">
                <div class="card-body">
                  <h5 class="card-title">Huerta</h5>
                  <p class="card-text">Nos aseguramos que nuestros ingredientes sean los más naturales y frescos posibles.</p>
                </div>
              </div>
            </div>
            <!-- Tarjeta 2: Equipo de chefs -->
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga (3).jpg" class="card-img-top" alt="Equipo de chefs">
                <div class="card-body">
                  <h5 class="card-title">Nuestros Chefs</h5>
                  <p class="card-text">Profesionales apasionados creando experiencias culinarias inolvidables.</p>
                </div>
              </div>
            </div>
            <!-- Tarjeta 3: Patio infantil -->
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 galeria-card">
                <img src="estilos/imagenes/descarga.jpg" class="card-img-top" alt="Patio infantil">
                <div class="card-body">
                  <h5 class="card-title">Patio para los niños</h5>
                  <p class="card-text">Espacio para que los padres puedan dejar que sus niños se diviertan.</p>
                </div>
              </div>
            </div>
            <!-- Tarjeta 4: Rincón especial -->
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

      <!-- Sección de Información Adicional con imagen y características -->
      <section class="seccion-admin">
        <div class="container">
          <div class="row align-items-stretch">
            <!-- Columna de texto con información y características -->
            <div class="col-md-6 d-flex flex-column justify-content-center">
              <div class="pe-md-4">
                <h3>Visita Nuestro Local</h3>
                <p class="lead">Te invitamos a descubrir por qué La Chacra Gourmet es más que un restaurante, es una experiencia.</p>
                <!-- Lista de características del restaurante -->
                <ul class="list-unstyled mb-4">
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Ambiente climatizado</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Estacionamiento gratuito</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Acceso para personas con movilidad reducida</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Wi-Fi gratuito</li>
                  <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Patio con juegos infantiles</li>
                </ul>
                <!-- Botones de acción -->
                <div class="mt-auto">
                  <a href="reservas1.php" class="btn btn-primary me-3">Reservar Mesa</a>
                  <a href="menu.php" class="btn btn-outline-primary">Ver Menú</a>
                </div>
              </div>
            </div>
            <!-- Columna de imagen -->
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

    <!-- Footer de la página -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - GALERÍA</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="reservas1.php" class="btn-enlace">Hacer Reserva</a>
      </div>
    </footer>
  </div>

  <!-- Bootstrap JavaScript para funcionalidad de componentes -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>