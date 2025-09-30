<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_promocion'])) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $condiciones = mysqli_real_escape_string($conexion, $_POST['condiciones']);
    $duracion = $_POST['duracion'];
    $estado = 'activa';

    $sql = "INSERT INTO promocion (titulo, descripcion, tipo, condiciones, duracion, estado) 
            VALUES ('$titulo', '$descripcion', '$tipo', '$condiciones', '$duracion', '$estado')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Promoción creada correctamente";
    } else {
        $error = "Error al crear promoción: " . mysqli_error($conexion);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_promocion'])) {
    $cliente_id = $_POST['cliente_id'];
    $promocion_id = $_POST['promocion_id'];

   
    $sql_cliente_info = "SELECT `usuario_id usuario` FROM cliente WHERE `id cliente` = $cliente_id";
    $resultado_cliente_info = mysqli_query($conexion, $sql_cliente_info);
    
    if ($resultado_cliente_info && mysqli_num_rows($resultado_cliente_info) > 0) {
        $cliente_info = mysqli_fetch_assoc($resultado_cliente_info);
        $usuario_id = $cliente_info['usuario_id usuario'];

       
        $sql_verificar = "SELECT * FROM cliente_has_promocion 
                         WHERE `cliente_id cliente` = $cliente_id 
                         AND `promocion_id promocion` = $promocion_id";
        
        if (mysqli_num_rows(mysqli_query($conexion, $sql_verificar)) == 0) {
          
            $sql = "INSERT INTO cliente_has_promocion (`cliente_id cliente`, `cliente_usuario_id usuario`, `promocion_id promocion`) 
                    VALUES ($cliente_id, $usuario_id, $promocion_id)";

            if (mysqli_query($conexion, $sql)) {
                $mensaje = "Promoción asignada al cliente correctamente";
            } else {
                $error = "Error al asignar promoción: " . mysqli_error($conexion);
            }
        } else {
            $error = "El cliente ya tiene esta promoción asignada";
        }
    } else {
        $error = " No se pudo obtener la información completa del cliente";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_estado'])) {
    $promocion_id = $_POST['promocion_id'];
    $estado = $_POST['estado'];

    $sql = "UPDATE promocion SET estado = '$estado' WHERE `id promocion` = $promocion_id";
    mysqli_query($conexion, $sql);
    $mensaje = " Estado de promoción actualizado";
}


$promociones = mysqli_query($conexion, "SELECT * FROM promocion ORDER BY estado, `id promocion` DESC");


$clientes = mysqli_query($conexion, "
    SELECT c.`id cliente`, u.nombre, u.gmail 
    FROM cliente c 
    JOIN usuario u ON c.`usuario_id usuario` = u.`id usuario`
    ORDER BY u.nombre
");


$promociones_asignadas = mysqli_query($conexion, "
    SELECT cp.*, u.nombre as cliente_nombre, p.titulo as promocion_titulo
    FROM cliente_has_promocion cp
    JOIN cliente c ON cp.`cliente_id cliente` = c.`id cliente`
    JOIN usuario u ON c.`usuario_id usuario` = u.`id usuario`
    JOIN promocion p ON cp.`promocion_id promocion` = p.`id promocion`
    ORDER BY cp.`promocion_id promocion`
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Promociones</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .mensaje { background: #d4edda; color: #155724; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 15px 0; border-radius: 8px; }
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
        .card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 5px solid #9b59b6;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
            padding: 10px 20px; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
            font-size: 14px;
            margin: 2px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-success { background: #27ae60; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        .btn-info { background: #3498db; }
        .badge-activa { background: #27ae60; color: white; padding: 4px 8px; border-radius: 12px; }
        .badge-inactiva { background: #95a5a6; color: white; padding: 4px 8px; border-radius: 12px; }
        .promocion-card { 
            border: 2px solid #9b59b6; 
            border-radius: 10px; 
            padding: 15px; 
            margin: 10px 0;
            background: #f8f9fa;
        }
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
                <h1> Gestión de Promociones</h1>

                <?php if (isset($mensaje)): ?>
                    <div class="mensaje"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <h2>Crear Nueva Promoción</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Título de la Promoción:</label>
                            <input type="text" name="titulo" required placeholder="Ej: 2x1 en Postres" maxlength="15">
                        </div>
                        
                        <div class="form-group">
                            <label>Descripción:</label>
                            <textarea name="descripcion" required placeholder="Describe los beneficios de la promoción" maxlength="50"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Tipo de Promoción:</label>
                            <select name="tipo" required>
                                <option value="descuento">💰 Descuento</option>
                                <option value="2x1">2️⃣✖️1️⃣ 2x1</option>
                                <option value="combo">🍽️ Combo</option>
                                <option value="regalo">🎁 Regalo</option>
                                <option value="earlybird">🐦 Early Bird</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Condiciones:</label>
                            <input type="text" name="condiciones" placeholder="Ej: Válido de lunes a viernes" maxlength="10">
                        </div>
                        
                        <div class="form-group">
                            <label>Duración (días):</label>
                            <input type="number" name="duracion" required min="1" placeholder="30">
                        </div>
                        
                        <button type="submit" name="crear_promocion" class="btn btn-success"> Crear Promoción</button>
                    </form>
                </div>

              
                <div class="card">
                    <h2>Asignar Promoción a Cliente</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Seleccionar Cliente:</label>
                            <select name="cliente_id" required>
                                <option value="">Seleccionar cliente...</option>
                                <?php while($cliente = mysqli_fetch_assoc($clientes)): ?>
                                    <option value="<?php echo $cliente['id cliente']; ?>">
                                        <?php echo $cliente['nombre']; ?> (<?php echo $cliente['gmail']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Seleccionar Promoción Activa:</label>
                            <select name="promocion_id" required>
                                <option value="">Seleccionar promoción...</option>
                                <?php 
                                mysqli_data_seek($promociones, 0);
                                while($promocion = mysqli_fetch_assoc($promociones)): 
                                    if ($promocion['estado'] == 'activa'):
                                ?>
                                    <option value="<?php echo $promocion['id promocion']; ?>">
                                        <?php echo $promocion['titulo']; ?> - <?php echo $promocion['tipo']; ?>
                                    </option>
                                <?php 
                                    endif;
                                endwhile; 
                                ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="asignar_promocion" class="btn btn-info">🎁 Asignar Promoción</button>
                    </form>
                </div>

           
                <div class="card">
                    <h2>Promociones del Sistema</h2>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Condiciones</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        <?php 
                        mysqli_data_seek($promociones, 0);
                        while($promocion = mysqli_fetch_assoc($promociones)): 
                            $badge_class = $promocion['estado'] == 'activa' ? 'badge-activa' : 'badge-inactiva';
                        ?>
                            <tr>
                                <td>#<?php echo $promocion['id promocion']; ?></td>
                                <td><strong><?php echo $promocion['titulo']; ?></strong></td>
                                <td><?php echo $promocion['descripcion']; ?></td>
                                <td>
                                    <?php 
                                    $icono = '';
                                    switch($promocion['tipo']) {
                                        case 'descuento': $icono = '💰'; break;
                                        case '2x1': $icono = '2️⃣✖️1️⃣'; break;
                                        case 'combo': $icono = '🍽️'; break;
                                        case 'regalo': $icono = '🎁'; break;
                                        case 'earlybird': $icono = '🐦'; break;
                                    }
                                    echo $icono . ' ' . $promocion['tipo']; 
                                    ?>
                                </td>
                                <td><?php echo $promocion['condiciones']; ?></td>
                                <td><?php echo $promocion['duracion']; ?> días</td>
                                <td><span class="<?php echo $badge_class; ?>"><?php echo ucfirst($promocion['estado']); ?></span></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="<?php echo $promocion['id promocion']; ?>">
                                        <select name="estado" onchange="this.form.submit()" style="padding: 6px;">
                                            <option value="activa" <?php echo $promocion['estado']=='activa'?'selected':''; ?>>Activar</option>
                                            <option value="inactiva" <?php echo $promocion['estado']=='inactiva'?'selected':''; ?>>Desactivar</option>
                                        </select>
                                        <input type="hidden" name="cambiar_estado" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>

              
                <div class="card">
                    <h2>Promociones Asignadas a Clientes</h2>
                    <?php if (mysqli_num_rows($promociones_asignadas) > 0): ?>
                        <table>
                            <tr>
                                <th>Cliente</th>
                                <th>Promoción</th>
                                <th>Tipo</th>
                                <th>Fecha Asignación</th>
                            </tr>
                            <?php while($asignacion = mysqli_fetch_assoc($promociones_asignadas)): ?>
                                <tr>
                                    <td>👤 <?php echo $asignacion['cliente_nombre']; ?></td>
                                    <td>🎁 <?php echo $asignacion['promocion_titulo']; ?></td>
                                    <td>
                                        <?php 
                                        $icono = '';
                                        switch(explode(' ', $asignacion['promocion_titulo'])[0]) {
                                            case '2x1': $icono = '2️⃣✖️1️⃣'; break;
                                            case 'Descuento': $icono = '💰'; break;
                                            case 'Combo': $icono = '🍽️'; break;
                                            case 'Regalo': $icono = '🎁'; break;
                                            default: $icono = '🎯';
                                        }
                                        echo $icono;
                                        ?>
                                    </td>
                                    <td><?php echo date('d/m/Y'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d; padding: 20px;">
                            No hay promociones asignadas a clientes aún.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>