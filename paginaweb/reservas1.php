<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciar_sesion.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas - La Chacra Gourmet</title>
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
      <!-- CAMBIO: banner-reservas en lugar de banner-admin -->
      <section class="banner-reservas">
        <h1>Reservas</h1>
      </section>

      <section class="seccion-admin">
        <form class="formulario-admin" action="reservas.php" method="POST">
          <h2>Realizar Reserva</h2>

          <div class="fila-formulario">
            <div class="grupo-formulario">
              <label for="cantidad">Cantidad de personas</label>
              <input type="number" name="personas" placeholder="Número de personas" required min="1" max="20">
            </div>
            
            <div class="grupo-formulario">
              <label for="hora">Hora de inicio</label>
              <input type="time" name="hora" required>
            </div>
          </div>

          <div class="fila-formulario">
            <div class="grupo-formulario">
              <label for="fecha">Fecha</label>
              <input type="date" name="fecha" required>
            </div>

            <!-- NUEVO CAMPO DESCRIPCIÓN CON ESTILOS APLICADOS -->
            <div class="grupo-formulario">
              <label for="descripcion">Descripción (opcional)</label>
              <textarea name="descripcion" placeholder="Comentarios adicionales..." rows="3"></textarea>
            </div>
          </div>

          <!-- CAMBIO: btn-reserva en lugar de btn-admin -->
          <button type="submit" name="confirmar" class="btn-reserva">Confirmar Reserva</button>
        </form>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - RESERVAS</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="menu.php" class="btn-enlace">Ver Menú</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>