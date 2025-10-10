<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redes y Pagos - La Chacra Gourmet</title>
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
          <li><a href="zona_staff.php">Mozos orden</a></li>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
        </ul>
      </nav>
      <div class="botones-sesion">
        <?php if (isset($_SESSION['id_usuario'])): ?>
          <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
          <a href="cerrar_sesion.php" class="btn-logout" role="button">Cerrar sesión</a>
        <?php else: ?>
          <a href="iniciar_sesion.html" class="btn-login" role="button">Iniciar sesión</a>
          <a href="registrarse_cliente.html" class="btn-register" role="button">Registrarse</a>
        <?php endif; ?>
      </div>
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
  
  <style>
    .redes-sociales, .metodos-pago, .horario-atencion {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border: 2px solid #C98A5E;
    }

    .contacto-item, .pago-item, .horario-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .contacto-item:last-child, .pago-item:last-child, .horario-item:last-child {
      border-bottom: none;
    }

    .contacto-item i, .pago-item i {
      font-size: 1.5rem;
      color: #C98A5E;
      width: 30px;
      text-align: center;
    }

    .contacto-item strong, .pago-item strong, .horario-item strong {
      color: #5B3A29;
      display: block;
      margin-bottom: 5px;
    }

    .contacto-item p, .pago-item p, .horario-item p {
      color: #666;
      margin: 0;
    }

    .horario-item {
      justify-content: space-between;
      padding: 12px 0;
    }

    .horario-item strong {
      color: #5B3A29;
    }

    .horario-item p {
      color: #C98A5E;
      font-weight: 600;
    }

    @media (max-width: 768px) {
      .contacto-item, .pago-item, .horario-item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
      }

      .horario-item {
        flex-direction: row;
        text-align: left;
      }
    }
  </style>
</body>
</html>