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
</head>
<body>
  <main class="detalle-plato" style="padding:20px;">
    <a href="menu.php" style="display:inline-block; margin-bottom:20px;">← Volver al menú</a>

    <h1><?php echo htmlspecialchars($plato['nombre']); ?></h1>
    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($plato['categoria']); ?></p>
    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($plato['nombre']); ?>" style="max-width:400px; width:100%; height:auto; display:block; margin:10px 0;">
    <p><strong>Precio:</strong> $<?php echo htmlspecialchars($plato['precio']); ?></p>
    <p><strong>Descripción completa:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($plato['descripcion'])); ?></p>
  </main>
</body>
</html>
