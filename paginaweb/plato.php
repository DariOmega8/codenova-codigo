<?php
session_start();
include 'conexion.php';

if (!isset($_GET['id'])) {
    echo "Plato no especificado.";
    exit;
}

$id_plato = intval($_GET['id']);

// Consulta CORREGIDA: usando el nombre correcto de la columna `menu_id menu`
$sql = "SELECT p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen, m.tipo AS categoria
        FROM plato p
        INNER JOIN menu m ON p.`menu_id menu` = m.id_menu
        WHERE p.plato_id = $id_plato";

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
// Manejo de imagen BLOB
if (isset($plato['imagen']) && !empty($plato['imagen'])) {
    $img = 'data:image/jpeg;base64,' . base64_encode($plato['imagen']);
} else {
    $img = $imagen_default;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($plato['nombre']); ?> - La Chacra Gourmet</title>
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
        <h1><?php echo htmlspecialchars($plato['nombre']); ?></h1>
      </section>

      <section class="seccion-admin">
        <div class="detalle-plato">
          <div class="plato-imagen">
            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($plato['nombre']); ?>">
          </div>
          <div class="plato-info">
            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($plato['categoria']); ?></p>
            <p><strong>Precio:</strong> <span class="precio">$<?php echo number_format($plato['precio'], 2); ?></span></p>
            <div class="descripcion-completa">
              <h3>Descripción:</h3>
              <p><?php echo nl2br(htmlspecialchars($plato['descripcion'])); ?></p>
            </div>
          </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
          <a href="menu.php" class="btn-admin">Volver al Menú</a>
          <a href="reservas1.php" class="btn-admin" style="background-color: #C98A5E;">Hacer Reserva</a>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - DETALLE DEL PLATO</div>
      <div class="footer-buttons">
        <a href="menu.php" class="btn-enlace">Volver al Menú</a>
        <a href="inicio.php" class="btn-enlace">Ir al Inicio</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>