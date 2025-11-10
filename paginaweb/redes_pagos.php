<?php
// Inicia la sesión para acceder a variables de sesión del usuario
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Configuración básica del documento HTML -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redes y Pagos - La Chacra Gourmet</title>
  <!-- Hoja de estilo con versión dinámica para evitar cache -->
  <link rel="stylesheet" href="estilos/estilo_general.css?v=<?php echo time(); ?>">
  <!-- Iconos de FontAwesome -->
  <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
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
            <!-- Muestra enlaces de login y registro para usuarios no autenticados -->
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar sesión</a></li>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenido Principal de la página -->
    <main class="contenido-principal">
      <!-- Banner principal de la página -->
      <section class="banner-admin">
        <h1>Redes Sociales y Métodos de Pago</h1>
      </section>

      <!-- Sección de información de contacto y redes sociales -->
      <section class="seccion-admin">
        <div class="informacion">
          <div class="redes-sociales">
            <h2>📱 Redes Sociales y Contacto</h2>
            <!-- Elemento de información de teléfono -->
            <div class="contacto-item">
              <i class="fas fa-phone"></i>
              <div>
                <strong>Número de teléfono:</strong>
                <p>+34 947 494</p>
              </div>
            </div>
            <!-- Elemento de información de email -->
            <div class="contacto-item">
              <i class="fas fa-envelope"></i>
              <div>
                <strong>Email:</strong>
                <p>chacragourmet@gmail.com</p>
              </div>
            </div>
            <!-- Elemento de información de Instagram -->
            <div class="contacto-item">
              <i class="fab fa-instagram"></i>
              <div>
                <strong>Instagram:</strong>
                <p>@lachacragourmet</p>
              </div>
            </div>
            <!-- Elemento de información de Facebook -->
            <div class="contacto-item">
              <i class="fab fa-facebook"></i>
              <div>
                <strong>Facebook:</strong>
                <p>La Chacra Gourmet</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Sección de métodos de pago aceptados -->
      <section class="seccion-admin">
        <div class="metodos-pago">
          <h2>💳 Métodos de Pago Aceptados</h2>
          <!-- Método de pago con tarjeta -->
          <div class="pago-item">
            <i class="fas fa-credit-card"></i>
            <div>
              <strong>Tarjeta de Crédito y Débito</strong>
              <p>Aceptamos todas las tarjetas principales: Visa, MasterCard, American Express</p>
            </div>
          </div>
          <!-- Método de pago en efectivo -->
          <div class="pago-item">
            <i class="fas fa-money-bill-wave"></i>
            <div>
              <strong>Efectivo</strong>
              <p>Pago en efectivo en moneda local</p>
            </div>
          </div>
          <!-- Método de pago móvil -->
          <div class="pago-item">
            <i class="fas fa-mobile-alt"></i>
            <div>
              <strong>Pago Móvil</strong>
              <p>Transferencias bancarias y billeteras digitales</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Sección de horarios de atención -->
      <section class="seccion-admin">
        <div class="horario-atencion">
          <h2>🕒 Horario de Atención</h2>
          <!-- Horario para días de semana -->
          <div class="horario-item">
            <strong>Lunes a Viernes:</strong>
            <p>12:00 PM - 11:00 PM</p>
          </div>
          <!-- Horario para fines de semana -->
          <div class="horario-item">
            <strong>Sábados y Domingos:</strong>
            <p>12:00 PM - 12:00 AM</p>
          </div>
          <!-- Horario para días festivos -->
          <div class="horario-item">
            <strong>Días Festivos:</strong>
            <p>12:00 PM - 10:00 PM</p>
          </div>
        </div>
      </section>
    </main>

    <!-- Pie de página -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - REDES Y PAGOS</div>
      <div class="footer-buttons">
        <!-- Enlaces de navegación en el footer -->
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="reservas1.php" class="btn-enlace">Hacer Reserva</a>
      </div>
    </footer>
  </div>

  <!-- Script de Bootstrap para funcionalidades adicionales -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>