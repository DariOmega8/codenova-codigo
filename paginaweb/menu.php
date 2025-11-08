<?php
session_start();
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Menú - La Chacra Gourmet</title>
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
        <h1>Menú del Restaurante</h1>
      </section>

      <?php
      // Consulta para menús disponibles
      $sqlCat = "SELECT id_menu, tipo FROM menu WHERE estado = 'disponible'";
      $resCat = mysqli_query($conexion, $sqlCat);
      if (!$resCat) {
        echo "<div class='mensaje-error'>Error al leer categorías: " . mysqli_error($conexion) . "</div>";
      } else {
        while ($cat = mysqli_fetch_assoc($resCat)) {
          $id_menu = (int)$cat['id_menu'];
          echo "<section class='seccion-admin'>";
          echo "<h2>" . htmlspecialchars($cat['tipo']) . "</h2>";

          // Consulta CORREGIDA: usando el nombre correcto de la columna `menu_id menu`
          $sqlPlatos = "SELECT p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen
                        FROM plato p
                        WHERE p.`menu_id menu` = $id_menu";
          $resPlatos = mysqli_query($conexion, $sqlPlatos);

          if (!$resPlatos) {
            echo "<div class='mensaje-error'>Error al leer platos: " . mysqli_error($conexion) . "</div>";
          } else {
            echo "<div class='contenedor-platos'>";
            while ($plato = mysqli_fetch_assoc($resPlatos)) {
              $id_plato = isset($plato['plato_id']) ? $plato['plato_id'] : 0;
              $nombre = isset($plato['nombre']) ? $plato['nombre'] : 'Sin nombre';
              $descripcion = isset($plato['descripcion']) ? $plato['descripcion'] : '';
              $descripcion_corta = mb_strlen($descripcion) > 120 ? mb_substr($descripcion,0,120) . '...' : $descripcion;
              $precio = isset($plato['precio']) ? number_format($plato['precio'], 2) : '---';

              $imagen_default = "estilos/imagenes/balatro.png";
              $img = $imagen_default;
             
              // Manejo de imagen BLOB
              if (isset($plato['imagen']) && !empty($plato['imagen'])) {
                // Si es un BLOB, convertirlo a base64
                $img = 'data:image/jpeg;base64,' . base64_encode($plato['imagen']);
              }

              echo "<article class='plato'>";
              echo "  <a href='plato.php?id=" . intval($id_plato) . "' class='plato-link'>";
              echo "    <img src='" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($nombre) . "'>";
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

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - MENÚ</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="reservas1.php" class="btn-enlace">Hacer Reserva</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>