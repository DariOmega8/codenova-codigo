<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redes y Pagos</title>
  <link rel="stylesheet" href="ryp.css">
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
        <section class="barra-busqueda">
            <input type="text" placeholder="Buscar...">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </section>

      <aside class="botones-sesion">
        <?php if (isset($_SESSION['id_usuario'])): ?>
          <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
          <a href="cerrar_sesion.php" class="btn-logout" role="button">Cerrar sesión</a>
        <?php else: ?>
          <a href="iniciar_sesion.html" class="btn-login" role="button">Iniciar sesión</a>
          <a href="registrarse_cliente.html" class="btn-register" role="button">Registrarse</a>
           <?php endif; ?>
      </aside>

        <h1>Redes y pagos</h1>

        <article class="informacion">
            <section class="redes">
                <h2>Redes Sociales</h2>
                <ul>
                    <li><strong>Numero de teléfono:</strong> Momo ponele onda</li>
                    <li><strong>Gmail:</strong> que auris de virgo momo</li>
                    <li><strong>Instagram:</strong> bien de payaso te vestiste hoy momo</li>
                </ul>
            </section>

            <section class="pagos">
                <h2>Métodos de Pago</h2>
                <ul>
                    <li><strong>Tarjeta de crédito y débito</strong></li>
                    <li><strong>Efectivo</strong></li>
                </ul>
            </section>
        </article>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

</body>
</html>
