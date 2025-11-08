<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redes y Pagos - La Chacra Gourmet</title>
  <link rel="stylesheet" href="estilos/estilo_general.css?v=<?php echo time(); ?>">
  <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
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
        <h1>Redes Sociales y Métodos de Pago</h1>
      </section>

      <section class="seccion-admin">
        <div class="informacion">
          <div class="redes-sociales">
            <h2>📱 Redes Sociales y Contacto</h2>
            <div class="contacto-item">
              <i class="fas fa-phone"></i>
              <div>
                <strong>Número de teléfono:</strong>
                <p>+34 947 494</p>
              </div>
            </div>
            <div class="contacto-item">
              <i class="fas fa-envelope"></i>
              <div>
                <strong>Email:</strong>
                <p>chacragourmet@gmail.com</p>
              </div>
            </div>
            <div class="contacto-item">
              <i class="fab fa-instagram"></i>
              <div>
                <strong>Instagram:</strong>
                <p>@lachacragourmet</p>
              </div>
            </div>
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

      <section class="seccion-admin">
        <div class="metodos-pago">
          <h2>💳 Métodos de Pago Aceptados</h2>
          <div class="pago-item">
            <i class="fas fa-credit-card"></i>
            <div>
              <strong>Tarjeta de Crédito y Débito</strong>
              <p>Aceptamos todas las tarjetas principales: Visa, MasterCard, American Express</p>
            </div>
          </div>
          <div class="pago-item">
            <i class="fas fa-money-bill-wave"></i>
            <div>
              <strong>Efectivo</strong>
              <p>Pago en efectivo en moneda local</p>
            </div>
          </div>
          <div class="pago-item">
            <i class="fas fa-mobile-alt"></i>
            <div>
              <strong>Pago Móvil</strong>
              <p>Transferencias bancarias y billeteras digitales</p>
            </div>
          </div>
        </div>
      </section>

      <section class="seccion-admin">
        <div class="horario-atencion">
          <h2>🕒 Horario de Atención</h2>
          <div class="horario-item">
            <strong>Lunes a Viernes:</strong>
            <p>12:00 PM - 11:00 PM</p>
          </div>
          <div class="horario-item">
            <strong>Sábados y Domingos:</strong>
            <p>12:00 PM - 12:00 AM</p>
          </div>
          <div class="horario-item">
            <strong>Días Festivos:</strong>
            <p>12:00 PM - 10:00 PM</p>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - REDES Y PAGOS</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="reservas1.php" class="btn-enlace">Hacer Reserva</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>