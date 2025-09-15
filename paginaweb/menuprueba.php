<?php
session_start();
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Menú</title>
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <link rel="stylesheet" href="estilos/menu.css">
</head>
<body>
  <main class="principal">

    <header class="menu">
      <nav>
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reserva1.php">Reservas</a></li>
          <li><a href="zona_staff.html">Mozos orden</a></li>
          <li><a href="historia.php">Historia</a></li>
        </ul>
      </nav>
    </header>

    <section class="contenido">
      <h1 style="color:#fff; margin-bottom:20px;">Menú del restaurante</h1>

      <?php
      
      $sqlCat = "SELECT `id menu` AS id_menu, tipo FROM `menu`";
      $resCat = mysqli_query($conexion, $sqlCat);
      if (!$resCat) {
          echo "<p style='color:orange;'>Error al leer categorías: " . mysqli_error($conexion) . "</p>";
      } else {
          while ($cat = mysqli_fetch_assoc($resCat)) {
              $id_menu = (int)$cat['id_menu'];
              echo "<section class='categoria'>";
              echo "<h2>" . htmlspecialchars($cat['tipo']) . "</h2>";

              $sqlPlatos = "SELECT p.`id platos` AS id_plato, p.nombre, p.descripcion, p.precio
                            FROM `platos` p
                            WHERE p.`menu_id menu` = $id_menu";
              $resPlatos = mysqli_query($conexion, $sqlPlatos);

              if (!$resPlatos) {
                  echo "<p style='color:orange;'>Error al leer platos: " . mysqli_error($conexion) . "</p>";
              } else {
                  
                  while ($plato = mysqli_fetch_assoc($resPlatos)) {
                      
                      $id_plato = isset($plato['id_plato']) ? $plato['id_plato'] : 0;
                      $nombre = isset($plato['nombre']) ? $plato['nombre'] : 'Sin nombre';
                      $descripcion = isset($plato['descripcion']) ? $plato['descripcion'] : '';
                      
                      $descripcion_corta = mb_strlen($descripcion) > 120 ? mb_substr($descripcion,0,120) . '...' : $descripcion;
                      $precio = isset($plato['precio']) ? $plato['precio'] : '---';

                      $imagen_default = "estilos/imagenes/balatro.png";
                      $img = $imagen_default;
                     
                      if (isset($plato['imagen']) && !empty($plato['imagen'])) {
                          $img = $plato['imagen'];
                      }

                      echo "<article>";
                      echo "  <a href='plato.php?id=" . intval($id_plato) . "' class='plato-link'>";
                      echo "    <img src='" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($nombre) . "'>";
                      echo "    <div class='info'>";
                      echo "      <h3>" . htmlspecialchars($nombre) . "</h3>";
                      echo "      <p>" . htmlspecialchars($descripcion_corta) . "</p>";
                      echo "      <span class='precio'>Precio $" . htmlspecialchars($precio) . "</span>";
                      echo "    </div>";
                      echo "  </a>";
                      echo "</article>";
                  }
              }

              echo "</section>";
          }
      }
      ?>

    </section>
  </main>
</body>
</html>

