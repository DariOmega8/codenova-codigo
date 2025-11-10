<?php
// Inicia la sesión para acceder a variables de sesión del usuario
session_start();
// Incluye el archivo de conexión a la base de datos
include 'conexion.php';

// Verifica si se ha proporcionado un ID de plato en la URL
if (!isset($_GET['id'])) {
    echo "Plato no especificado.";
    exit;
}

// Obtiene y sanitiza el ID del plato (convierte a entero para seguridad)
$id_plato = intval($_GET['id']);

// Consulta SQL para obtener los detalles del plato específico
// NOTA: Usa el nombre exacto de la columna en la base de datos `menu_id menu`
$sql = "SELECT p.plato_id, p.nombre, p.descripcion, p.precio, p.imagen, m.tipo AS categoria
        FROM plato p
        INNER JOIN menu m ON p.`menu_id menu` = m.id_menu
        WHERE p.plato_id = $id_plato";

// Ejecuta la consulta
$res = mysqli_query($conexion, $sql);

// Manejo de errores en la consulta
if (!$res) {
    echo "Error en consulta: " . mysqli_error($conexion);
    exit;
}

// Verifica si se encontró el plato
if (mysqli_num_rows($res) == 0) {
    echo "Plato no encontrado.";
    exit;
}

// Obtiene los datos del plato como array asociativo
$plato = mysqli_fetch_assoc($res);

// Configuración de imagen por defecto
$imagen_default = "estilos/imagenes/balatro.png";

// Lógica para determinar qué imagen mostrar
if (isset($plato['imagen']) && !empty($plato['imagen'])) {
    $ruta_imagen = "imagenes_platos/" . $plato['imagen'];
    // Verifica si la imagen existe físicamente en el servidor
    if (file_exists($ruta_imagen)) {
        $img = $ruta_imagen;
    } else {
        $img = $imagen_default;
    }
} else {
    $img = $imagen_default;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Configuración básica del documento HTML -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Título dinámico con el nombre del plato -->
  <title><?php echo htmlspecialchars($plato['nombre']); ?> - La Chacra Gourmet</title>
  <!-- Hojas de estilo -->
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="contenedor-principal">
    <!-- Encabezado principal del sitio -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
      </div>
      <!-- Navegación principal -->
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <!-- Muestra enlace para empleados solo si el usuario es empleado -->
          <?php if (isset($_SESSION['es_empleado']) && $_SESSION['es_empleado'] === true): ?>
            <li><a href="zona_staff.php">Mozos orden</a></li>
          <?php endif; ?>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="galeria.php">Galería</a></li>
          <!-- Muestra panel de administración solo para administradores -->
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
          <!-- Enlaces condicionales según el estado de autenticación -->
          <?php if (isset($_SESSION['id_usuario'])): ?>
            <!-- Muestra botón de cerrar sesión con el nombre del usuario -->
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>)</a></li>
          <?php else: ?>
            <!-- Muestra enlaces de login y registro para usuarios no autenticados -->
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar sesión</a></li>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenido Principal de la página -->
    <main class="contenido-principal">
      <!-- Banner principal con el nombre del plato -->
      <section class="banner-admin">
        <h1><?php echo htmlspecialchars($plato['nombre']); ?></h1>
      </section>

      <!-- Sección de detalles del plato -->
      <section class="seccion-admin">
        <div class="detalle-plato">
          <!-- Contenedor de la imagen del plato -->
          <div class="plato-imagen">
            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($plato['nombre']); ?>" onerror="this.src='<?php echo $imagen_default; ?>'">
          </div>
          <!-- Contenedor de la información del plato -->
          <div class="plato-info">
            <!-- Muestra la categoría del plato -->
            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($plato['categoria']); ?></p>
            <!-- Muestra el precio formateado -->
            <p><strong>Precio:</strong> <span class="precio">$<?php echo number_format($plato['precio'], 2); ?></span></p>
            <!-- Muestra la descripción completa del plato -->
            <div class="descripcion-completa">
              <h3>Descripción:</h3>
              <!-- nl2br convierte saltos de línea en <br> para mantener el formato -->
              <p><?php echo nl2br(htmlspecialchars($plato['descripcion'])); ?></p>
            </div>
          </div>
        </div>
        
        <!-- Botones de acción -->
        <div style="text-align: center; margin-top: 30px;">
          <a href="menu.php" class="btn-admin">Volver al Menú</a>
          <a href="reservas1.php" class="btn-admin" style="background-color: #C98A5E;">Hacer Reserva</a>
        </div>
      </section>
    </main>

    <!-- Pie de página -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - DETALLE DEL PLATO</div>
      <div class="footer-buttons">
        <!-- Enlaces de navegación en el footer -->
        <a href="menu.php" class="btn-enlace">Volver al Menú</a>
        <a href="inicio.php" class="btn-enlace">Ir al Inicio</a>
      </div>
    </footer>
  </div>

  <!-- Script de Bootstrap para funcionalidades adicionales -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>