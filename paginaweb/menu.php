<?php
// Inicia la sesión para acceder a variables de sesión del usuario
session_start();
// Incluye el archivo de conexión a la base de datos
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Configuración básica del documento HTML -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Menú - La Chacra Gourmet</title>
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
        <h1>Menú del Restaurante</h1>
      </section>

      <?php
      // Consulta SQL para obtener los menús disponibles
      $sqlCat = "SELECT id_menu, tipo FROM menu WHERE estado = 'disponible'";
      $resCat = mysqli_query($conexion, $sqlCat);
      
      // Manejo de errores en la consulta de categorías
      if (!$resCat) {
        echo "<div class='mensaje-error'>Error al leer categorías: " . mysqli_error($conexion) . "</div>";
      } else {
        // Itera sobre cada categoría de menú
        while ($cat = mysqli_fetch_assoc($resCat)) {
          $id_menu = (int)$cat['id_menu'];
          echo "<section class='seccion-admin'>";
          echo "<h2>" . htmlspecialchars($cat['tipo']) . "</h2>";

          // Consulta SQL para obtener los platos de esta categoría de menú
          // NOTA: Usa el nombre exacto de la columna en la base de datos `menu_id menu`
          $sqlPlatos = "SELECT p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen
                        FROM plato p
                        WHERE p.`menu_id menu` = $id_menu";
          $resPlatos = mysqli_query($conexion, $sqlPlatos);

          // Manejo de errores en la consulta de platos
          if (!$resPlatos) {
            echo "<div class='mensaje-error'>Error al leer platos: " . mysqli_error($conexion) . "</div>";
          } else {
            echo "<div class='contenedor-platos'>";
            // Itera sobre cada plato de la categoría actual
            while ($plato = mysqli_fetch_assoc($resPlatos)) {
              // Extrae y formatea los datos del plato
              $id_plato = isset($plato['plato_id']) ? $plato['plato_id'] : 0;
              $nombre = isset($plato['nombre']) ? $plato['nombre'] : 'Sin nombre';
              $descripcion = isset($plato['descripcion']) ? $plato['descripcion'] : '';
              // Acorta la descripción si es muy larga
              $descripcion_corta = mb_strlen($descripcion) > 120 ? mb_substr($descripcion,0,120) . '...' : $descripcion;
              $precio = isset($plato['precio']) ? number_format($plato['precio'], 2) : '---';

              // Lógica para determinar la imagen a mostrar
              $imagen_default = "estilos/imagenes/balatro.png";
              $ruta_imagen = $imagen_default;
             
              // Verifica si hay una imagen específica en la base de datos
              if (isset($plato['imagen']) && !empty($plato['imagen'])) {
                  $ruta_posible = "imagenes_platos/" . $plato['imagen'];
                  // Comprueba si el archivo de imagen existe en el servidor
                  if (file_exists($ruta_posible)) {
                      $ruta_imagen = $ruta_posible;
                  }
              }

              // Genera el HTML para cada plato
              echo "<article class='plato'>";
              echo "  <a href='plato.php?id=" . intval($id_plato) . "' class='plato-link'>";
              echo "    <img src='" . htmlspecialchars($ruta_imagen) . "' alt='" . htmlspecialchars($nombre) . "' onerror=\"this.src='$imagen_default'\">";
              echo "    <div class='plato-info'>";
              echo "      <h3>" . htmlspecialchars($nombre) . "</h3>";
              echo "      <p>" . htmlspecialchars($descripcion_corta) . "</p>";
              echo "      <span class='precio'>$" . htmlspecialchars($precio) . "</span>";
              echo "    </div>";
              echo "  </a>";
              echo "</article>";
            }
            echo "</div>";
          }
          echo "</section>";
        }
      }
      ?>
    </main>

    <!-- Pie de página -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - MENÚ</div>
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