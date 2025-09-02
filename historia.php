<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historia</title>
  <link rel="stylesheet" href="historia.css">
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

        <h1>Historia</h1>

        <article class="informacion">  
            <h2>Fundación del restaurante en la chacra</h2>
            <p>Momo, el famoso streamer italiano que vive en
               Argentina, fundó el restaurante en la chacra como
               un homenaje a sus raíces y a la cultura local. Su 
               comunidad lo apoya con entusiasmo, a pesar de 
               bromear sobre sus costumbres y ocurrencias.</p>

            <h2>Inspiración y visión gourmet</h2>
            <p>La visión gourmet de Momo está marcada por su
               amor por la cocina italiana y argentina. Sus 
               seguidores suelen bromear con que Momo nunca 
               permite que le revuelvan los ñoquis, 
               convirtiendo esta preferencia en uno de los 
               memes más populares de su canal.</p>

            <h2>Crecimiento y evolución</h2>
            <p>El restaurante creció rápidamente gracias a la 
               fama de Momo y a los memes que lo rodean. 
               Entre los más conocidos está el de “Momo con 
               dos ladrillos en la mano”, que representa su 
               actitud decidida y su estilo único para resolver
               problemas.</p>

            <h2>Reconocimientos y logros</h2>
            <p>Además de recibir premios gastronómicos, Momo es 
               reconocido por su comunidad por frases como 
               “banana” y por sus reacciones divertidas ante 
               los platos estrella del restaurante. Los memes 
               sobre sus gustos culinarios y su personalidad 
               han ayudado a difundir su historia.</p>

            <h2>Compromiso con la calidad y la comunidad</h2>
            <p>Momo mantiene un fuerte compromiso con la calidad 
               y la comunidad, siempre interactuando con sus 
               seguidores y aceptando con humor los memes que 
               surgen, como el famoso “momo dio dislike a la 
               página” o sus aventuras con los balatreros.</p>
        </article>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>


</body>
</html>
