<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio la chackra</title>
    <link rel="stylesheet" href="inicio_estilo.css">
    <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
</head>

<body> 


  <div class="principal">
        <div class="menu">
          <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="redes_pagos.php">Redes y pagos</a></li>
                <li><a href="reservas.php">Reservas</a></li>
                <li><a href="zona_staff.html">Mozos orden</a></li>
                <li><a href="historia.php">Historia</a></li>
              </ul>
          </nav>
        </div>

    <div class="contenido">
   <div class="barra-busqueda">
      <input type="text" placeholder="Buscar...">
      <button class="lupa"><i class="fa-solid fa-magnifying-glass"></i></button>
  </div>
  <div class="botones-sesion">
    <?php if (isset($_SESSION['id_usuario'])): ?>
      <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
      <a href="cerrar_sesion.php" class="btn-logout" role="button">Cerrar sesión</a>
    <?php else: ?>
      <a href="iniciar_sesion.html" class="btn-login" role="button">Iniciar sesión</a>
      <a href="registrarse_cliente.html" class="btn-register" role="button">Registrarse</a>
    <?php endif; ?>
  </div>

    <div class="banner">
        <h1>La chakra gourmet</h1>
    </div>

    <div class="informacion">
        <h2>informacion</h2>
         <h3>Vision</h3>
         Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim sunt facere aperiam quaerat, 
         tempore nihil expedita aliquid doloremque maiores quidem est iusto ut aliquam inventore in ad
          itaque corrupti quia.
         <h3>Mision</h3>
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim sunt facere aperiam quaerat,
          tempore nihil expedita aliquid doloremque maiores quidem est iusto ut aliquam inventore in ad
          itaque corrupti quia.
        <h3>objetivos</h3>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim sunt facere aperiam quaerat,
            tempore nihil expedita aliquid doloremque maiores quidem est iusto ut aliquam inventore in ad
            itaque corrupti quia.
    </div>
    
    <div class="platos-estrella">
        <h2>platos estrella</h2>
          <div class="plato">
             <img src="imagenes/milanesa.jpg" alt="">
            <div class="plato-info">
            <h3>milanesa con fideo</h3>
            <h4>Descripcion del plato</h4>
            <p>es una milanesa con fideo</p>
            </div>
          </div> 
          <div class="plato">
             <img src="imagenes/ñoquis.png" alt="">
            <div class="plato-info">
            <h3>ñoquis revolvidos</h3>
            <h4>Descripcion del plato</h4>
            <p>momo dio dislike a la pagina</p>
            </div>
          </div>
          <div class="plato">
            <img src="imagenes/balatrero.png" alt="">
            <div class="plato-info">
            <h3>balatrero balatraz</h3>
            <h4>Descripcion del plato</h4>
            <p>los balatreros dicen banana</p>
            </div>
          </div>
    </div>

    <div class="ubicacion">
         <h2>ubicacion</h2>
            <p>cupiditate dolorem magni porro error nulla voluptatum consequatur aliquid.</p>
            <img src="imagenes/ñoquis.png" alt="">
    </div>
    
  </div>  
</div> 
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

</body>

</html>