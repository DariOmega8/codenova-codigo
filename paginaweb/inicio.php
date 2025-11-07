<?php
session_start();
include "conexion.php";

// Consulta para obtener los 3 platos más pedidos
$query_platos_populares = "
    SELECT p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen, 
           COUNT(pd.pedido_pedido_id) as total_pedidos
    FROM plato p
    LEFT JOIN pedido_detalle pd ON p.plato_id = pd.plato_plato_id
    GROUP BY p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen
    ORDER BY total_pedidos DESC
    LIMIT 3
";

$result_platos = mysqli_query($conexion, $query_platos_populares);
$platos_estrella = [];
if ($result_platos) {
    while ($row = mysqli_fetch_assoc($result_platos)) {
        $platos_estrella[] = $row;
    }
}
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
          <?php if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador']): ?>
            <li><a href="administracion.php" class="btn-admin-header">Administración</a></li>
          <?php endif; ?>
          <?php if ((isset($_SESSION['es_empleado']) && $_SESSION['es_empleado']) || (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'])): ?>
            <li><a href="zona_staff.php" class="btn-staff-header">Zona Mozos</a></li>
          <?php endif; ?>
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

      <!-- Contenido principal -->
      <main class="contenido-principal">
        <section class="banner">
          <h1>Donde el campo y el sabor se encuentran.</h1>
        </section>

        <section class="informacion" id="informacion">
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
        
        <section class="platos-estrella" id="platos-estrella">
          <h2>Platos Más Pedidos</h2>
          <div class="contenedor-platos">
            <?php if (!empty($platos_estrella)): ?>
              <?php foreach($platos_estrella as $plato): ?>
                <article class="plato">
                    <div class="plato-imagen-container">
    <?php
    // Manejo de imagen BLOB - VERSIÓN MEJORADA
    $imagen_default = "estilos/imagenes/comida.png";
    if (isset($plato['imagen']) && !empty($plato['imagen']) && $plato['imagen'] != 'NULL') {
        // Verificar que el BLOB sea una imagen válida
        if (strlen($plato['imagen']) > 100) {
            $img_data = $plato['imagen'];
            // Verificar si es una imagen BLOB válida
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->buffer($img_data);
            if (strpos($mime_type, 'image/') === 0) {
                $img = 'data:image/jpeg;base64,' . base64_encode($img_data);
            } else {
                $img = $imagen_default;
            }
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
              <!-- Platos por defecto si no hay datos en la BD -->
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

        <section class="ubicacion" id="ubicacion">
  <h2>Ubicación</h2>
  <p>Visítanos en nuestro acogedor local rodeado de naturaleza</p>
  <a href="https://www.google.com/maps/place/La+Chacra/@-30.2712849,-57.5975084,13.5z/data=!4m6!3m5!1s0x95acd7fae122a4d3:0xaea5a6f448772d48!8m2!3d-30.2784935!4d-57.5924924!16s%2Fg%2F11h39rcmks?entry=ttu&g_ep=EgoyMDI1MTEwNC4xIKXMDSoASAFQAw%3D%3D" 
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
  
  <script>
    // Smooth scroll para los enlaces del sidebar
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