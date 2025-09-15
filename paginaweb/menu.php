<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <link rel="stylesheet" href="estilos/menu.css">
    <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

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
        <button class="lupa"><i class="fa-solid fa-magnifying-glass"></i></button>
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

      <section class="entrantes">
        <h2>Entrantes</h2>
        <article>
          <img src="estilos/imagenes/entrante.png" alt="">
          <div>
          <h3>tabla de quesos</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
       <article>
          <img src="estilos/imagenes/entrante.png" alt="">
          <div>
          <h3>tabla de quesos</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/entrante.png" alt="">
          <div>
          <h3>tabla de quesos</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/entrante.png" alt="">
          <div>
          <h3>tabla de quesos</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/entrante.png" alt="">
          <div>
          <h3>tabla de quesos</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        
      </section>
      
      <section class="Comidas">
        <h2>Comidas</h2>
        <article>
          <img src="estilos/imagenes/comida.png" alt="">
          <div>
          <h3>Asado</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/comida.png" alt="">
          <div>
          <h3>Asado</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/comida.png" alt="">
          <div>
          <h3>Asado</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/comida.png" alt="">
          <div>
          <h3>Asado</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/comida.png" alt="">
          <div>
          <h3>Asado</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>

      </section>

      <section class="bebidas">
        <h2>Bebidas</h2>
         <article>
          <img src="estilos/imagenes/bebida.png" alt="">
          <div>
          <h3>Coca cola</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/bebida.png" alt="">
          <div>
          <h3>Coca cola</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/bebida.png" alt="">
          <div>
          <h3>Coca cola</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/bebida.png" alt="">
          <div>
          <h3>Coca cola</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
         <article>
          <img src="estilos/imagenes/bebida.png" alt="">
          <div>
          <h3>Coca cola</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
      </section>

      <section class="postres">
        <h2>Postres</h2>
         <article>
          <img src="estilos/imagenes/postres.png" alt="">
          <div>
          <h3>Pastel de chocolate blanco con fresa</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/postres.png" alt="">
          <div>
          <h3>Pastel de chocolate blanco con fresa</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/postres.png" alt="">
          <div>
          <h3>Pastel de chocolate blanco con fresa</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/postres.png" alt="">
          <div>
          <h3>Pastel de chocolate blanco con fresa</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
        <article>
          <img src="estilos/imagenes/postres.png" alt="">
          <div>
          <h3>Pastel de chocolate blanco con fresa</h3>
          <p>lorem gjkgbg kjgnjgb mgkgjng mgjngj jmngjhbngjgng</p>
          <span class="precio">Precio $340</span>
          </div>
        </article>
      </section>

    </section>  
  </main> 
    
</body>
</html>