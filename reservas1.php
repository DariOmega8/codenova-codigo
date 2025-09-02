<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciar_sesion.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas</title>
  <link rel="stylesheet" href="reservas.css">
</head>
<body>

<main class="principal">
    <header class="menu">
        <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="redes_pagos.php">Redes y pagos</a></li>
                <li><a href="reservas.php">Reservas</a></li>
                <li><a href="zona_staff.html">Mozos orden</a></li>
                <li><a href="historia.php">Historia</a></li>
            </ul>
        </nav>
    </header>

    <section class="contenido">
        <aside class="barra-busqueda">
            <input type="text" placeholder="Buscar...">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
       </aside>

      <aside class="botones-sesion">
        <?php if (isset($_SESSION['id_usuario'])): ?>
          <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
          <a href="cerrar_sesion.php" class="btn-logout" role="button">Cerrar sesión</a>
        <?php else: ?>
          <a href="iniciar_sesion.html" class="btn-login" role="button">Iniciar sesión</a>
          <a href="registrarse_cliente.html" class="btn-register" role="button">Registrarse</a>
        <?php endif; ?>
      </aside>
   

        <form class="formulario" action="reservas.php" method="POST">
            <h2>Reserva</h2>

            <label for="cantidad">Cantidad de personas</label>
            <input type="number" name="personas" placeholder="Número de personas" required>
            
            <label for="hora">Hora de inicio</label>
            <input type="time" name="hora" required>
            
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" required>

            <button type="submit" name="confirmar" class="confirmar-reserva">Confirmar</button>
        </form>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>


</body>
</html>
