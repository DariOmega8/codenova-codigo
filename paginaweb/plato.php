<?php
session_start();
include 'conexion.php';

if (!isset($_GET['id'])) {
    echo "Plato no especificado.";
    exit;
}

$id_plato = intval($_GET['id']);

$sql = "SELECT p.`id platos` AS id_plato, p.nombre, p.descripcion, p.precio, m.tipo AS categoria
        FROM `platos` p
        INNER JOIN `menu` m ON p.`menu_id menu` = m.`id menu`
        WHERE p.`id platos` = $id_plato";

$res = mysqli_query($conexion, $sql);

if (!$res) {
    echo "Error en consulta: " . mysqli_error($conexion);
    exit;
}

if (mysqli_num_rows($res) == 0) {
    echo "Plato no encontrado.";
    exit;
}

$plato = mysqli_fetch_assoc($res);

$imagen_default = "estilos/imagenes/balatro.png";
$img = isset($plato['imagen']) && !empty($plato['imagen']) ? $plato['imagen'] : $imagen_default;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($plato['nombre']); ?></title>
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <link rel="stylesheet" href="estilos/plato.css">
  <link rel="stylesheet" href="estilos/reponsive.css">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
</head>
<body>
  <main class="principal" style="padding:20px;">
     
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
      <article class="detalle-plato">
        <h1><?php echo htmlspecialchars($plato['nombre']); ?></h1>
        <p><strong>Categoría:</strong> <?php echo htmlspecialchars($plato['categoria']); ?></p>
        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($plato['nombre']); ?>" style="max-width:400px; width:100%; height:auto; display:block; margin:10px 0;">
        <p><strong>Precio:</strong> $<?php echo htmlspecialchars($plato['precio']); ?></p>
        <p><strong>Descripción completa:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($plato['descripcion'])); ?></p>
     </article>
    </section>
  </main>
</body>
</html>
