<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historia</title>
  <link rel="stylesheet" href="estilos/historia.css">
  <link rel="stylesheet" href="estilos/reponsive.css">
</head>
<body>


<main class="principal">
    <header class="menu">
        <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="redes_pagos.php">Redes y pagos</a></li>
                <li><a href="reservas1.php">Reservas</a></li>
                <li><a href="zona_staff.html">Mozos orden</a></li>
                <li><a href="historia.php">Historia</a></li>
            </ul>
        </nav>
    </header>

    <section class="contenido">
        <header class="barra-busqueda">
            <input type="text" placeholder="Buscar...">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </header>

        <header class="botones-sesion">
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
                <a href="cerrar_sesion.php" class="btn-logout" role="button">Cerrar sesión</a>
            <?php else: ?>
                <a href="iniciar_sesion.html" class="btn-login" role="button">Iniciar sesión</a>
                <a href="registrarse_cliente.html" class="btn-register" role="button">Registrarse</a>
            <?php endif; ?>
        </header>

        <h1>Historia</h1>

        <article class="informacion">  
            <h2>Fundación del restaurante en la chacra</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis 
                cupiditate reprehenderit, eum accusamus facere consectetur exercitationem.
                 Suscipit, minima praesentium. Minima saepe culpa itaque eum aperiam vel iste
                  delectus, a adipisci!.</p>

            <h2>Inspiración y visión gourmet</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis architecto 
                ab voluptatum suscipit et atque odio optio fugit. Cupiditate placeat harum
                 voluptatum voluptatem neque repellendus earum minus veritatis, nostrum a!.</p>

            <h2>Crecimiento y evolución</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et voluptate
                 veniam vitae esse ullam recusandae ratione obcaecati, quam facere quia
                  fuga totam temporibus, necessitatibus blanditiis culpa in eius, magnam 
                  aliquid!.</p>

            <h2>Reconocimientos y logros</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Error aperiam nemo illum natus sit fugiat nostrum quis excepturi
                 nulla ab quam, vel ex facere consectetur dicta quia modi harum impedit!.</p>

            <h2>Compromiso con la calidad y la comunidad</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur 
                magnam non nam quaerat voluptatem maxime facilis, soluta maiores 
                molestiae eius eligendi, nostrum, voluptatum id est distinctio 
                reprehenderit dolorum? Eligendi, veniam?.</p>
        </article>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>


</body>
</html>
