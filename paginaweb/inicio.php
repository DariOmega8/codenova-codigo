<?php
// Inicia la sesión para acceder a variables de sesión
session_start();
// Incluye el archivo de conexión a la base de datos
include "conexion.php";

// Consulta SQL para obtener los 3 platos más pedidos
$query_platos_populares = "
    SELECT p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen, 
           COUNT(pd.pedido_pedido_id) as total_pedidos
    FROM plato p
    LEFT JOIN pedido_detalle pd ON p.plato_id = pd.plato_plato_id
    GROUP BY p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen
    ORDER BY total_pedidos DESC
    LIMIT 3
";

// Ejecuta la consulta en la base de datos
$result_platos = mysqli_query($conexion, $query_platos_populares);
$platos_estrella = [];
// Si la consulta fue exitosa, procesa los resultados
if ($result_platos) {
    while ($row = mysqli_fetch_assoc($result_platos)) {
        $platos_estrella[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio La Chacra Gourmet</title>
    <!-- Enlace a hoja de estilos con versión dinámica para evitar cache -->
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
          <li><a href="reservas1.php">Reservar</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="galeria.php">Galería</a></li>
          <!-- Muestra enlace de administración solo si el usuario es admin -->
          <?php if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador']): ?>
            <li><a href="administracion.php" class="btn-admin-header">Administración</a></li>
          <?php endif; ?>
          <!-- Muestra zona staff para empleados y administradores -->
          <?php if ((isset($_SESSION['es_empleado']) && $_SESSION['es_empleado']) || (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'])): ?>
            <li><a href="zona_staff.php" class="btn-staff-header">Zona Mozos</a></li>
          <?php endif; ?>
          <!-- Enlaces para usuarios no autenticados -->
          <?php if (!isset($_SESSION['id_usuario'])): ?>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar Sesion</a></li>
          <?php else: ?>
            <!-- Muestra botón de cerrar sesión para usuarios autenticados -->
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar sesión</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenedor principal con sidebar y contenido -->
    <div class="contenido-con-sidebar">
      <!-- Barra lateral de navegación -->
      <aside class="sidebar">
        <ul>
          <li><a href="#informacion">
            <i class="fas fa-info-circle sidebar-icon"></i>
            <span>Información</span>
          </a></li>
          <li><a href="#platos-estrella">
            <i class="fas fa-star sidebar-icon"></i>
            <span>Platos Estrella</span>
          </a></li>
          <li><a href="#ubicacion">
            <i class="fas fa-map-marker-alt sidebar-icon"></i>
            <span>Ubicación</span>
          </a></li>
        </ul>
      </aside>

      <!-- Contenido principal de la página -->
      <main class="contenido-principal">
        <!-- Banner principal -->
        <section class="banner">
          <h1>Donde el campo y el sabor se encuentran.</h1>
        </section>

        <!-- Sección de información corporativa -->
        <section class="informacion" id="informacion">
          <h2>Información</h2>
          <div class="info-item">
            <h3>Visión</h3>
            <p>Texto placeholder sobre la visión del restaurante...</p>
          </div>
          <div class="info-item">
            <h3>Misión</h3>
            <p>Texto placeholder sobre la misión del restaurante...</p>
          </div>
          <div class="info-item">
            <h3>Objetivos</h3>
            <p>Texto placeholder sobre los objetivos del restaurante...</p>
          </div>
        </section>
        
        <!-- Sección de platos más pedidos -->
        <section class="platos-estrella" id="platos-estrella">
          <h2>Platos Más Pedidos</h2>
          <div class="contenedor-platos">
            <?php if (!empty($platos_estrella)): ?>
              <!-- Itera sobre los platos obtenidos de la base de datos -->
              <?php foreach($platos_estrella as $plato): ?>
                <article class="plato">
                    <div class="plato-imagen-container">
    <?php
    // Lógica para mostrar la imagen del plato
    $imagen_default = "estilos/imagenes/comida.png";
    if (isset($plato['imagen']) && !empty($plato['imagen'])) {
        $ruta_imagen = "imagenes_platos/" . $plato['imagen'];
        // Verifica si la imagen existe en el servidor
        if (file_exists($ruta_imagen)) {
            $img = $ruta_imagen;
        } else {
            $img = $imagen_default;
        }
    } else {
        $img = $imagen_default;
    }
    ?>
    <img src="<?php echo $img; ?>" 
         alt="<?php echo htmlspecialchars($plato['nombre']); ?>"
         onerror="this.src='<?php echo $imagen_default; ?>'; this.style.display='block';">
</div>
                  <div class="plato-info">
                    <h3><?php echo htmlspecialchars($plato['nombre']); ?></h3>
                    <h4>$<?php echo number_format($plato['precio'], 2); ?></h4>
                    <p><?php echo htmlspecialchars($plato['descripcion'] ?? 'Delicioso plato de nuestra carta'); ?></p>
                    <div class="plato-popularidad">
                      <i class="fas fa-fire"></i>
                      <span>Pedido <?php echo $plato['total_pedidos'] ?? '0'; ?> veces</span>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <!-- Muestra platos por defecto si no hay datos en la BD -->
              <article class="plato">
                <div class="plato-imagen-container">
                  <img src="estilos/imagenes/comida.png" alt="Milanesa a la Napolitana">
                </div>
                <div class="plato-info">
                  <h3>Milanesa a la Napolitana</h3>
                  <h4>$1,200.00</h4>
                  <p>Carne empanizada con salsa de tomate y queso derretido</p>
                  <div class="plato-popularidad">
                    <i class="fas fa-fire"></i>
                    <span>Muy popular</span>
                  </div>
                </div>
              </article>

              <!-- Plato por defecto 2 -->
              <article class="plato">
                <div class="plato-imagen-container">
                  <img src="estilos/imagenes/comida.png" alt="Ñoquis de la Casa">
                </div>
                <div class="plato-info">
                  <h3>Ñoquis de la Casa</h3>
                  <h4>$980.00</h4>
                  <p>Pasta casera con salsa cremosa y hierbas aromáticas</p>
                  <div class="plato-popularidad">
                    <i class="fas fa-fire"></i>
                    <span>Muy popular</span>
                  </div>
                </div>
              </article>

              <!-- Plato por defecto 3 -->
              <article class="plato">
                <div class="plato-imagen-container">
                  <img src="estilos/imagenes/comida2.jpeg" alt="Especialidad del Chef">
                </div>
                <div class="plato-info">
                  <h3>Especialidad del Chef</h3>
                  <h4>$1,500.00</h4>
                  <p>Preparación exclusiva con ingredientes de temporada</p>
                  <div class="plato-popularidad">
                    <i class="fas fa-fire"></i>
                    <span>Muy popular</span>
                  </div>
                </div>
              </article>
            <?php endif; ?>
          </div>
        </section>

        <!-- Sección de ubicación -->
        <section class="ubicacion" id="ubicacion">
  <h2>Ubicación</h2>
  <p>Visítanos en nuestro acogedor local rodeado de naturaleza</p>
  <!-- Enlace a Google Maps -->
  <a href="https://www.google.com/maps/place/La+Chacra/@-30.2712849,-57.5975084,13.5z/data=!4m6!3m5!1s0x95acd7fae122a4d3:0xaea5a6f448772d48!8m2!3d-30.2784935!4d-57.5924924!16s%2Fg%2F11h39rcmks?entry=ttu&g_ep=EgoyMDI5MTEwNC4xIKXMDSoASAFQAw%3D%3D" 
     target="_blank" class="mapa-container">
    <img src="estilos/imagenes/imagen-local.jpeg" alt="Ubicación del restaurante La Chacra" class="mapa-imagen"
         onerror="this.src='estilos/imagenes/comida.png'">
    <div class="mapa-overlay">
      <i class="fas fa-map-marked-alt"></i>
      <span>Ver en Google Maps</span>
    </div>
  </a>
</section>
      </main>
    </div>

    <!-- Pie de página -->
    <footer>
      <div class="social-icons">
        <!-- Enlaces a redes sociales -->
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

  <!-- Script de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  
  <!-- Script para scroll suave -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarLinks = document.querySelectorAll('.sidebar a[href^="#"]');
      
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          
          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
    });
  </script>
  
</body>
</html>