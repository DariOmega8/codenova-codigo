<?php
session_start();
include "conexion.php";

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zona Administrativa</title>
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <style>
    .admin-section {
      background:#fff; padding:15px; margin:15px 0; border-radius:8px;
    }
    .admin-section h3 { margin-top:0; }
  </style>
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
      <h2>Bienvenido, <?php echo htmlspecialchars($nombre['nombre']); ?></h2>

      <div class="admin-section">
        <h3>Creación de usuarios</h3>
        <form method="POST" action="crear_usuario.php">
          <input type="text" name="nombre" placeholder="Nombre" required>
          <input type="email" name="gmail" placeholder="Correo" required>
          <input type="password" name="contraseña" placeholder="Contraseña" required>
          <input type="text" name="categorizacion" placeholder="Categorización (admin, cliente, empleado)" required>
          <button type="submit">Crear</button>
        </form>
      </div>

      <div class="admin-section">
        <h3>Reservas</h3>
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM reserva ORDER BY fecha DESC LIMIT 10");
        while ($row = mysqli_fetch_assoc($res)) {
          echo "<p>Reserva #{$row['id reserva']} — {$row['fecha']} — Estado: {$row['estado']}</p>";
        }
        ?>
      </div>

      <div class="admin-section">
        <h3>Cambiar plato</h3>
        <form method="POST" action="editar_plato.php">
          <input type="number" name="id_plato" placeholder="ID Plato" required>
          <input type="text" name="nombre" placeholder="Nuevo nombre">
          <input type="text" name="descripcion" placeholder="Nueva descripción">
          <input type="number" step="0.01" name="precio" placeholder="Nuevo precio">
          <button type="submit">Actualizar</button>
        </form>
      </div>

      <div class="admin-section">
        <h3>Cambiar menú</h3>
        <form method="POST" action="editar_menu.php">
          <input type="number" name="id_menu" placeholder="ID Menú" required>
          <input type="text" name="tipo" placeholder="Nuevo tipo">
          <button type="submit">Actualizar</button>
        </form>
      </div>

      <div class="admin-section">
        <h3>Información de usuarios</h3>
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM usuario");
        while ($row = mysqli_fetch_assoc($res)) {
          echo "<p>{$row['id usuario']} — {$row['nombre']} — {$row['gmail']}</p>";
        }
        ?>
      </div>

    </section>
  </main>
</body>
</html>
