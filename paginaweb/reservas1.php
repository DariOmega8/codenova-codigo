<?php
// Inicia la sesión para acceder a las variables de sesión del usuario
session_start();

// Verifica si el usuario está autenticado, si no, redirige a la página de login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciar_sesion.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Configuración básica del documento HTML -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas - La Chacra Gourmet</title>
  <!-- Hojas de estilo -->
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="contenedor-principal">
    <!-- Encabezado principal del sitio -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
      </div>
      <!-- Navegación principal -->
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <!-- Muestra enlace para empleados solo si el usuario es empleado -->
          <?php if (isset($_SESSION['es_empleado']) && $_SESSION['es_empleado'] === true): ?>
            <li><a href="zona_staff.php">Mozos orden</a></li>
          <?php endif; ?>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="galeria.php">Galería</a></li>
          <!-- Muestra panel de administración solo para administradores -->
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
          <!-- Enlaces condicionales según el estado de autenticación -->
          <?php if (isset($_SESSION['id_usuario'])): ?>
            <!-- Muestra botón de cerrar sesión con el nombre del usuario -->
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>)</a></li>
          <?php else: ?>
            <!-- Estos enlaces no se mostrarán porque la página requiere autenticación -->
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar sesión</a></li>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenido Principal de la página -->
    <main class="contenido-principal">
      <!-- Banner específico para la página de reservas -->
      <section class="banner-reservas">
        <h1>Reservas</h1>
      </section>

      <!-- Sección del formulario de reservas -->
      <section class="seccion-admin">
        <!-- Formulario que envía los datos a reservas.php mediante POST -->
        <form class="formulario-admin" action="reservas.php" method="POST">
          <h2>Realizar Reserva</h2>

          <!-- Primera fila del formulario con dos campos -->
          <div class="fila-formulario">
            <!-- Grupo para la cantidad de personas -->
            <div class="grupo-formulario">
              <label for="cantidad">Cantidad de personas</label>
              <input type="number" name="personas" placeholder="Número de personas" required min="1" max="20">
            </div>
            
            <!-- Grupo para la hora de la reserva -->
            <div class="grupo-formulario">
              <label for="hora">Hora de inicio</label>
              <input type="time" name="hora" required>
            </div>
          </div>

          <!-- Segunda fila del formulario con dos campos -->
          <div class="fila-formulario">
            <!-- Grupo para la fecha de la reserva -->
            <div class="grupo-formulario">
              <label for="fecha">Fecha</label>
              <input type="date" name="fecha" required>
            </div>

            <!-- Grupo para la descripción opcional -->
            <div class="grupo-formulario">
              <label for="descripcion">Descripción (opcional)</label>
              <textarea name="descripcion" placeholder="Comentarios adicionales..." rows="3"></textarea>
            </div>
          </div>

          <!-- Botón para enviar el formulario -->
          <button type="submit" name="confirmar" class="btn-reserva">Confirmar Reserva</button>
        </form>
      </section>
    </main>

    <!-- Pie de página -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - RESERVAS</div>
      <div class="footer-buttons">
        <!-- Enlaces de navegación en el footer -->
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="menu.php" class="btn-enlace">Ver Menú</a>
      </div>
    </footer>
  </div>

  <!-- Script de Bootstrap para funcionalidades adicionales -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>