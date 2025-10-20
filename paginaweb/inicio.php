<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio La Chacra Gourmet</title>
    <link rel="stylesheet" href="estilos/estilo_general.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
</head>

<body> 
  <div class="contenedor-principal">
    <!-- Header superior -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
      </div>
      <nav class="navegacion-principal">
        <ul>
          <li><a href="reservas1.php">Reservar</a></li>
          <li><a href="menu.php">Menu</a></li>
          <?php if (!isset($_SESSION['id_usuario'])): ?>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar Sesion</a></li>
          <?php else: ?>
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar sesión</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenido principal con sidebar -->
    <div class="contenido-con-sidebar">
      <!-- Sidebar -->
      <aside class="sidebar">
        <ul>
          <li><a href="zona_staff.php">
            
            <span>zona staff</span>
          </a></li>
          <li><a href="administracion.php">
            
            <span>zona administrativa</span>
          </a></li>
          <li><a href="inicio.php">
            
            <span>Inicio</span>
          </a></li>
          <li><a href="#">
            
            <span>Extras</span>
          </a></li>
          <li><a href="#">
            
            <span>Postres</span>
          </a></li>
          <li><a href="#">
            
            <span>Bebidas</span>
          </a></li>
          <li><a href="#">
            
            <span>Comanda</span>
          </a></li>
        </ul>
      </aside>
      </aside>

      <!-- Contenido principal -->
      <main class="contenido-principal">
        <section class="banner">
          <h1>Donde el campo y el sabor se encuentran.</h1>
        </section>

        <section class="informacion">
          <h2>Información</h2>
          <div class="info-item">
            <h3>Visión</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim sunt facere aperiam quaerat, 
            tempore nihil expedita aliquid doloremque maiores quidem est iusto ut aliquam inventore in ad
            itaque corrupti quia.</p>
          </div>
          <div class="info-item">
            <h3>Misión</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim sunt facere aperiam quaerat,
            tempore nihil expedita aliquid doloremque maiores quidem est iusto ut aliquam inventore in ad
            itaque corrupti quia.</p>
          </div>
          <div class="info-item">
            <h3>Objetivos</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim sunt facere aperiam quaerat,
            tempore nihil expedita aliquid doloremque maiores quidem est iusto ut aliquam inventore in ad
            itaque corrupti quia.</p>
          </div>
        </section>
        
        <section class="platos-estrella">
          <h2>Platos Estrella</h2>
          <div class="contenedor-platos">
            <article class="plato">
              <img src="estilos/imagenes/comida.png" alt="Plato estrella 1"  onerror="this.src='estilos/imagenes/balatro.png'">
              <div class="plato-info">
                <h3>Milanesa a la Napolitana</h3>
                <h4>Clásica y deliciosa</h4>
                <p>Carne empanizada con salsa de tomate y queso derretido</p>
              </div>
            </article>

            <article class="plato">
              <img src="estilos/imagenes/comida.png" alt="Plato estrella 2" onerror="this.src='estilos/imagenes/balatro.png'">
              <div class="plato-info">
                 <h3>Ñoquis de la Casa</h3>
                <h4>Frescos y artesanales</h4>
                <p>Pasta casera con salsa cremosa y hierbas aromáticas</p>
              </div>
            </article>

            <article class="plato">
              <img src="estilos/imagenes/comida2.jpeg" alt="Plato estrella 3" onerror="this.src='estilos/imagenes/milanesa.jpg'">
              <div class="plato-info">
                <h3>Especialidad del Chef</h3>
                <h4>Sabores únicos</h4>
                <p>Preparación exclusiva con ingredientes de temporada</p>
              </div>
            </article>
          </div>
        </section>

        <section class="ubicacion">
          <h2>Ubicación</h2>
          <p>Visítanos en nuestro acogedor local rodeado de naturaleza</p>
          <img src="estilos/imagenes/imagen-local.jpeg" alt="Ubicación del restaurante" onerror="this.style.display='none'">
        </section>
      </main>
    </div>

    <!-- Footer -->
    <footer>
      <div class="social-icons">
        <a href="#"><img src="estilos/imagenes/instagram.jpeg" alt="Instagram" onerror="this.style.display='none'"></a>
        <a href="#"><img src="estilos/imagenes/twitter.jpeg" alt="Twitter" onerror="this.style.display='none'"></a>
        <a href="#"><img src="estilos/imagenes/facebook.jpeg" alt="Facebook" onerror="this.style.display='none'"></a>
      </div>
      <div class="footer-texto">LA CHACRA GOURMET</div>
      <div class="footer-buttons">
        <a href="historia.php">Historia</a>
        <a href="redes_pagos.php">Contacto</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  
</body>
</html>