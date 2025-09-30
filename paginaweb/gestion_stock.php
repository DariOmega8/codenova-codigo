<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_producto'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $unidad_medida = mysqli_real_escape_string($conexion, $_POST['unidad_medida']);
    

    $sql_stock = "INSERT INTO stock (`unidad de medida`, `cantidad total`, `ultima actualizacion`) 
                 VALUES ('$unidad_medida', $cantidad, NOW())";
    
    if (mysqli_query($conexion, $sql_stock)) {
        $stock_id = mysqli_insert_id($conexion);
        
    
        $sql_producto = "INSERT INTO producto (cantidad, nombre, precio, `tipo de producto`, `stock_id stock`) 
                        VALUES ($cantidad, '$nombre', $precio, '$tipo', $stock_id)";
        
        if (mysqli_query($conexion, $sql_producto)) {
            $mensaje = "Producto agregado correctamente al stock";
        } else {
            $error = "Error al agregar producto: " . mysqli_error($conexion);
        }
    } else {
        $error = "Error al crear registro de stock: " . mysqli_error($conexion);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_stock'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad_movimiento = $_POST['cantidad_movimiento']; 
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    
 
    $sql_info_producto = "SELECT p.cantidad, p.`stock_id stock` 
                         FROM producto p 
                         WHERE p.`id producto` = $producto_id";
    $resultado_info = mysqli_query($conexion, $sql_info_producto);
    
    if ($resultado_info && mysqli_num_rows($resultado_info) > 0) {
        $producto_info = mysqli_fetch_assoc($resultado_info);
        $cantidad_actual = $producto_info['cantidad'];
        $stock_id = $producto_info['stock_id stock'];
        

        if ($tipo_movimiento == 'entrada') {
            $nueva_cantidad = $cantidad_actual + $cantidad_movimiento;
        } elseif ($tipo_movimiento == 'salida') {
            $nueva_cantidad = $cantidad_actual - $cantidad_movimiento;
        } else { 
            $nueva_cantidad = $cantidad_movimiento;
        }
        
      
        if ($nueva_cantidad < 0) {
            $error = "Error: La cantidad no puede ser negativa";
        } else {
         
            $sql_actualizar = "UPDATE producto SET cantidad = $nueva_cantidad WHERE `id producto` = $producto_id";
            
            if (mysqli_query($conexion, $sql_actualizar)) {
            
                $sql_stock = "UPDATE stock 
                             SET `cantidad total` = $nueva_cantidad, 
                                 `ultima actualizacion` = NOW() 
                             WHERE `id stock` = $stock_id";
                mysqli_query($conexion, $sql_stock);
                
               
                $sql_historial = "INSERT INTO historial (`descripcion`, `tipo de movimiento`, `cantidad`, `fecha`, `stock_id stock`) 
                                 VALUES ('$descripcion', '$tipo_movimiento', $cantidad_movimiento, NOW(), $stock_id)";
                mysqli_query($conexion, $sql_historial);
                
                $mensaje = "stock actualizado correctamente (De $cantidad_actual a $nueva_cantidad)";
            } else {
                $error = "Error al actualizar stock: " . mysqli_error($conexion);
            }
        }
    }
}


$productos = mysqli_query($conexion, "
    SELECT p.*, s.`unidad de medida`, s.`ultima actualizacion`
    FROM producto p 
    JOIN stock s ON p.`stock_id stock` = s.`id stock`
    ORDER BY p.`tipo de producto`, p.nombre
");


$stats_tipos = mysqli_query($conexion, "
    SELECT `tipo de producto`, COUNT(*) as total, SUM(cantidad) as cantidad_total
    FROM producto 
    GROUP BY `tipo de producto`
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Stock</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .mensaje { background: #d4edda; color: #155724; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .stats-container { display: flex; gap: 15px; margin: 20px 0; flex-wrap: wrap; }
        .stat-card { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 200px;
        }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, select, textarea { 
            padding: 12px; 
            width: 100%; 
            max-width: 400px; 
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background: #34495e; 
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background: #f8f9fa; }
        .btn { 
            padding: 12px 25px; 
            background: #3498db; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .btn-success { background: #27ae60; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        
        .tipo-bebida { border-left: 4px solid #3498db; }
        .tipo-alimento { border-left: 4px solid #e74c3c; }
        .tipo-limpieza { border-left: 4px solid #9b59b6; }
        .tipo-utensilio { border-left: 4px solid #f39c12; }
        
        .bajo-stock { background: #ffeaa7 !important; }
        .critico-stock { background: #fab1a0 !important; }
    </style>
</head>
<body>
    <main class="principal">
        <header class="menu">
            <nav>
                <ul>
                    <li><a href="inicio.php">Inicio</a></li>
                    <li><a href="administracion.php">Panel Admin</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <section class="contenido">
            <div class="container">
                <h1>Gestión de Stock</h1>
                
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

   
                <div class="stats-container">
                    <?php while($stat = mysqli_fetch_assoc($stats_tipos)): 
                        $icono = '';
                        $color = '';
                        switch($stat['tipo de producto']) {
                            case 'bebida': $icono = '🥤'; $color = '#3498db'; break;
                            case 'alimento': $icono = '🍎'; $color = '#e74c3c'; break;
                            case 'limpieza': $icono = '🧹'; $color = '#9b59b6'; break;
                            case 'utensilio': $icono = '🔪'; $color = '#f39c12'; break;
                        }
                    ?>
                        <div class="stat-card" style="border-left: 4px solid <?php echo $color; ?>;">
                            <h3><?php echo $icono; ?> <?php echo ucfirst($stat['tipo de producto']); ?></h3>
                            <p><strong>Productos:</strong> <?php echo $stat['total']; ?></p>
                            <p><strong>Stock total:</strong> <?php echo $stat['cantidad_total']; ?> unidades</p>
                        </div>
                    <?php endwhile; ?>
                </div>

                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                    <h2>Agregar Nuevo Producto</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Nombre del Producto:</label>
                            <input type="text" name="nombre" required placeholder="Ej: Coca-Cola 2L">
                        </div>
                        
                        <div class="form-group">
                            <label>Cantidad Inicial:</label>
                            <input type="number" name="cantidad" required min="0" placeholder="Ej: 50">
                        </div>
                        
                        <div class="form-group">
                            <label>Precio Unitario:</label>
                            <input type="number" step="0.01" name="precio" required placeholder="Ej: 5.50">
                        </div>
                        
                        <div class="form-group">
                            <label>Tipo de Producto:</label>
                            <select name="tipo" required>
                                <option value="bebida">🥤 Bebida</option>
                                <option value="alimento">🍎 Alimento</option>
                                <option value="limpieza">🧹 Limpieza</option>
                                <option value="utensilio">🔪 Utensilio</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Unidad de Medida:</label>
                            <input type="text" name="unidad_medida" placeholder="Ej: litros, kg, unidades" required>
                        </div>
                        
                        <button type="submit" name="agregar_producto" class="btn btn-success"> Agregar Producto</button>
                    </form>
                </div>

            
                <h2>Inventario Actual</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Tipo</th>
                        <th>Unidad</th>
                        <th>Última Actualización</th>
                        <th>Acciones</th>
                    </tr>
                    <?php while($producto = mysqli_fetch_assoc($productos)): 
                        $clase_tipo = 'tipo-' . $producto['tipo de producto'];
                        $clase_stock = '';
                        if ($producto['cantidad'] < 10) $clase_stock = 'critico-stock';
                        elseif ($producto['cantidad'] < 25) $clase_stock = 'bajo-stock';
                    ?>
                        <tr class="<?php echo $clase_tipo . ' ' . $clase_stock; ?>">
                            <td><?php echo $producto['id producto']; ?></td>
                            <td><strong><?php echo $producto['nombre']; ?></strong></td>
                            <td>
                                <span style="font-weight: bold; color: <?php echo $producto['cantidad'] < 10 ? '#e74c3c' : ($producto['cantidad'] < 25 ? '#f39c12' : '#27ae60'); ?>">
                                    <?php echo $producto['cantidad']; ?>
                                </span>
                            </td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td>
                                <?php 
                                $icono = '';
                                switch($producto['tipo de producto']) {
                                    case 'bebida': $icono = '🥤'; break;
                                    case 'alimento': $icono = '🍎'; break;
                                    case 'limpieza': $icono = '🧹'; break;
                                    case 'utensilio': $icono = '🔪'; break;
                                }
                                echo $icono . ' ' . ucfirst($producto['tipo de producto']); 
                                ?>
                            </td>
                            <td><?php echo $producto['unidad de medida']; ?></td>
                            <td><?php echo $producto['ultima actualizacion']; ?></td>
                               <td>
                                     <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                                       <input type="hidden" name="producto_id" value="<?php echo $producto['id producto']; ?>">
                                       <input type="number" name="cantidad_movimiento" placeholder="Cantidad" required 
                                       style="width: 80px; padding: 6px;" min="0">
                                      <select name="tipo_movimiento" required style="padding: 6px;">
                                      <option value="entrada">📥 Entrada</option>
                                      <option value="salida">📤 Salida</option>
                                      <option value="ajuste">⚙️ Ajuste</option>
                                      </select>
                                      <input type="text" name="descripcion" placeholder="Motivo" required 
                                      style="width: 120px; padding: 6px;">
                                      <button type="submit" name="actualizar_stock" class="btn btn-warning" style="padding: 6px 12px;">🔄</button>
                                 </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <h2>Historial de Movimientos</h2>
                <?php
                $historial = mysqli_query($conexion, "
                    SELECT h.*, p.nombre as producto_nombre 
                    FROM historial h 
                    JOIN producto p ON h.`stock_id stock` = p.`stock_id stock`
                    ORDER BY h.`fecha` DESC 
                    LIMIT 20
                ");
                ?>
                <table>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                    </tr>
                    <?php while($movimiento = mysqli_fetch_assoc($historial)): 
                        $color_tipo = '';
                        switch($movimiento['tipo de movimiento']) {
                            case 'entrada': $color_tipo = '#27ae60'; break;
                            case 'salida': $color_tipo = '#e74c3c'; break;
                            case 'ajuste': $color_tipo = '#f39c12'; break;
                        }
                    ?>
                        <tr>
                            <td><?php echo $movimiento['fecha']; ?></td>
                            <td><?php echo $movimiento['producto_nombre']; ?></td>
                            <td><?php echo $movimiento['descripcion']; ?></td>
                            <td style="color: <?php echo $color_tipo; ?>;">
                                <?php echo ucfirst($movimiento['tipo de movimiento']); ?>
                            </td>
                            <td><?php echo $movimiento['cantidad']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </section>
    </main>
</body>
</html>