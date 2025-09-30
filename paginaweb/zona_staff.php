<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if (!$_SESSION['es_empleado'] && !$_SESSION['es_administrador']) {

    header("Location: inicio.php?error=No tienes permisos para acceder a esta zona");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mesa'])) {
    $mesa_numero = $_POST['mesa'];
    $plato_principal = $_POST['pedido'] ?? '';
    $bebida = $_POST['bebida'] ?? '';
    $postre = $_POST['postre'] ?? '';
    $extra = $_POST['extra'] ?? '';
    $exclusiones = $_POST['exclusiones'] ?? '';
    

    $sql_mesa = "SELECT m.`id mesa`, m.`cliente_id cliente`, m.`cliente_usuario_id usuario`, 
                        c.`id cliente`, u.nombre as cliente_nombre
                 FROM mesa m 
                 JOIN cliente c ON m.`cliente_id cliente` = c.`id cliente`
                 JOIN usuario u ON c.`usuario_id usuario` = u.`id usuario`
                 WHERE m.numero = $mesa_numero AND m.estado = 'ocupada'";
    
    $resultado_mesa = mysqli_query($conexion, $sql_mesa);
    
    if ($resultado_mesa && mysqli_num_rows($resultado_mesa) > 0) {
        $mesa_data = mysqli_fetch_assoc($resultado_mesa);
        $mesa_id = $mesa_data['id mesa'];
        $cliente_id = $mesa_data['id cliente'];
        
     
        $sql_pedido = "INSERT INTO pedido (estado, fecha) VALUES ('pendiente', NOW())";
        mysqli_query($conexion, $sql_pedido);
        $pedido_id = mysqli_insert_id($conexion);
        
    
        $sql_relacion = "INSERT INTO mesa_has_pedido (`mesa_id mesa`, `mesa_cliente_id cliente`, 
                        `mesa_cliente_usuario_id usuario`, `pedido_id pedido`) 
                        VALUES ($mesa_id, $cliente_id, $cliente_id, $pedido_id)";
        mysqli_query($conexion, $sql_relacion);
        

        $platos_a_buscar = [];
        if (!empty($plato_principal)) $platos_a_buscar[] = $plato_principal;
        if (!empty($bebida)) $platos_a_buscar[] = $bebida;
        if (!empty($postre)) $platos_a_buscar[] = $postre;
        if (!empty($extra)) $platos_a_buscar[] = $extra;
        
        foreach ($platos_a_buscar as $nombre_plato) {
            $sql_plato = "SELECT `id platos` FROM platos WHERE nombre LIKE '%$nombre_plato%'";
            $resultado_plato = mysqli_query($conexion, $sql_plato);
            
            if ($resultado_plato && mysqli_num_rows($resultado_plato) > 0) {
                $plato_data = mysqli_fetch_assoc($resultado_plato);
                $plato_id = $plato_data['id platos'];
                
                $sql_agregar_plato = "INSERT INTO pedido_has_platos (`pedido_id pedido`, `platos_id platos`) 
                                     VALUES ($pedido_id, $plato_id)";
                mysqli_query($conexion, $sql_agregar_plato);
                
        
                function descontarStock($conexion, $nombre_producto, $tipo_producto) {
     
                     $tipos_automaticos = ['bebida', 'utensilio'];
     
                if (in_array($tipo_producto, $tipos_automaticos)) {
                   $sql_producto = "SELECT p.`id producto`, p.cantidad 
                        FROM producto p 
                        WHERE p.nombre LIKE '%$nombre_producto%' 
                        AND p.`tipo de producto` = '$tipo_producto'
                        LIMIT 1";
        
                $resultado = mysqli_query($conexion, $sql_producto);
        
                     if ($resultado && mysqli_num_rows($resultado) > 0) {
                     $producto = mysqli_fetch_assoc($resultado);
                     $nueva_cantidad = $producto['cantidad'] - 1;
            
                        if ($nueva_cantidad >= 0) {
                             $sql_actualizar = "UPDATE producto SET cantidad = $nueva_cantidad 
                                 WHERE `id producto` = {$producto['id producto']}";
                               mysqli_query($conexion, $sql_actualizar);
                 
                       
                             $sql_historial = "INSERT INTO historial (`descripcion`, `tipo de movimiento`, 
                                `cantidad`, `fecha`, `stock_id stock`) 
                                VALUES ('Venta de $nombre_producto', 'salida', 1, NOW(), 
                                (SELECT `stock_id stock` FROM producto WHERE `id producto` = {$producto['id producto']}))";
                                mysqli_query($conexion, $sql_historial);
                
                return true;
            }
        }
    }
    return false;
}
            }
        }
        
        $mensaje = "Pedido #$pedido_id creado correctamente para Mesa $mesa_numero";
    } else {
        $error = "Mesa $mesa_numero no encontrada o no está ocupada";
    }
}


$mesas_ocupadas = mysqli_query($conexion, "SELECT * FROM mesa WHERE estado = 'ocupada'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <link rel="stylesheet" href="estilos/zona_staff.css">
    <link rel="stylesheet" href="estilos/reponsive.css">
    <title>Mozos orden</title>
    <style>
        .mensaje { background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <main class="principal">
        <header class="menu">
            <nav>
                <ul>
                    <li><a href="inicio.php">Inicio</a></li>
                    <li><a href="redes_pagos.php">Redes y pagos</a></li>
                    <li><a href="reservas1.php">Reservas</a></li>
                    <li><a href="zona_staff.php">Mozos orden</a></li>
                    <li><a href="historia.php">Historia</a></li>
                    <li><a href="menu.php">Menu</a></li>
                    <?php 
                    if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
                        echo '<li><a href="administracion.php">Panel Admin</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </header>

        <section class="contenido">
             <div class="verificar-reserva-container">
                <a href="verificar_reserva.php" class="verificar-btn">🔍 Verificar Reserva</a>
            </div>
            <header class="barra-busqueda">
                <input type="text" placeholder="Buscar...">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </header>

            <?php if (isset($mensaje)): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form class="formulario" method="POST">
                <h2>Órdenes</h2>
                
                <label for="mesa">Mesa</label>
                <select id="mesa" name="mesa" required>
                    <option value="">Seleccionar mesa...</option>
                    <?php while($mesa = mysqli_fetch_assoc($mesas_ocupadas)): ?>
                        <option value="<?php echo $mesa['numero']; ?>">
                            Mesa <?php echo $mesa['numero']; ?> (Capacidad: <?php echo $mesa['capacidad']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="pedido">Plato principal</label>
                <input type="text" id="pedido" name="pedido" placeholder="Ej: Lomo Saltado">
                
                <label for="bebida">Bebidas</label>
                <input type="text" id="bebida" name="bebida" placeholder="Ej: Coca-Cola">
                
                <label for="postre">Postre</label>
                <input type="text" id="postre" name="postre" placeholder="Ej: Flan">

                <label for="extra">Extras</label>
                <input type="text" id="extra" name="extra" placeholder="Ej: Papas fritas">

                <label for="exclusiones">Exclusiones (Alergias/Preferencias)</label>
                <input type="text" id="exclusiones" name="exclusiones" placeholder="Ej: Sin gluten">

                <button type="submit" class="subir-pedido">Confirmar Pedido</button>
            </form>


            <div style="margin-top: 30px;">
                <h3>Pedidos Activos</h3>
                <table border="1" style="width: 100%;">
                    <tr>
                        <th>Pedido ID</th>
                        <th>Mesa</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                    <?php
                    $pedidos_activos = mysqli_query($conexion, "
                        SELECT p.`id pedido`, m.numero as mesa_numero, p.estado
                        FROM pedido p
                        JOIN mesa_has_pedido mp ON p.`id pedido` = mp.`pedido_id pedido`
                        JOIN mesa m ON mp.`mesa_id mesa` = m.`id mesa`
                        WHERE p.estado != 'entregado'
                    ");
                    
                    while($pedido = mysqli_fetch_assoc($pedidos_activos)) {
                        echo "<tr>
                                <td>#{$pedido['id pedido']}</td>
                                <td>Mesa {$pedido['mesa_numero']}</td>
                                <td>{$pedido['estado']}</td>
                                <td>
                                    <form method='POST' action='actualizar_pedido.php' style='display:inline;'>
                                        <input type='hidden' name='pedido_id' value='{$pedido['id pedido']}'>
                                        <select name='nuevo_estado' onchange='this.form.submit()'>
                                            <option value='pendiente'>Pendiente</option>
                                            <option value='preparacion'>En preparación</option>
                                            <option value='listo'>Listo para servir</option>
                                            <option value='entregado'>Entregado</option>
                                        </select>
                                    </form>
                                </td>
                              </tr>";
                    }
                    ?>
                </table>
            </div>

        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>