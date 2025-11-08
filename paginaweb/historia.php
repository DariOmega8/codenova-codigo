<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historia - La Chacra Gourmet</title>
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="contenedor-principal">
    <!-- Header -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
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

    <!-- Contenido Principal -->
    <main class="contenido-principal">
      <section class="banner-admin">
        <h1>Nuestra Historia</h1>
      </section>

      <section class="seccion-admin">
        <article class="informacion">  
          <h2>Fundación del restaurante en la chacra</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis 
            cupiditate reprehenderit, eum accusamus facere consectetur exercitationem.
            Suscipit, minima praesentium. Minima saepe culpa itaque eum aperiam vel iste
            delectus, a adipisci!.</p>

          <h2>Inspiración y visión gourmet</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis architecto 
            ab voluptatum suscipit et atque odio optio fugit. Cupiditate placeat harum
            voluptatum voluptatem neque repellendus earum minus veritatis, nostrum a!.</p>

          <h2>Crecimiento y evolución</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et voluptate
            veniam vitae esse ullam recusandae ratione obcaecati, quam facere quia
            fuga totam temporibus, necessitatibus blanditiis culpa in eius, magnam 
            aliquid!.</p>

          <h2>Reconocimientos y logros</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
            Error aperiam nemo illum natus sit fugiat nostrum quis excepturi
            nulla ab quam, vel ex facere consectetur dicta quia modi harum impedit!.</p>

          <h2>Compromiso con la calidad y la comunidad</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur 
            magnam non nam quaerat voluptatem maxime facilis, soluta maiores 
            molestiae eius eligendi, nostrum, voluptatum id est distinctio 
            reprehenderit dolorum? Eligendi, veniam?.</p>
        </article>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - NUESTRA HISTORIA</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="menu.php" class="btn-enlace">Ver Menú</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
