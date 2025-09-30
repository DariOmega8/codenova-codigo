<?php
session_start();
include "conexion.php";


if (!isset($_SESSION['es_empleado']) && !isset($_SESSION['es_administrador'])) {
    header("Location: inicio.php");
    exit();
}

$cliente = null;
$reservas = [];
$mesas_disponibles = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar_cliente'])) {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    
    $sql_cliente = "SELECT u.`id usuario`, u.nombre, u.gmail, c.`id cliente`
                    FROM usuario u 
                    JOIN cliente c ON u.`id usuario` = c.`usuario_id usuario`
                    WHERE u.gmail = '$email'";
    
    $resultado = mysqli_query($conexion, $sql_cliente);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $cliente = mysqli_fetch_assoc($resultado);
        $cliente_id = $cliente['id cliente'];
        
        $hoy = date('Y-m-d');
        $sql_reservas = "SELECT * FROM reserva 
                        WHERE `cliente_id cliente` = $cliente_id 
                        AND fecha = '$hoy' 
                        AND estado = 'Pendiente'";
        
        $reservas_result = mysqli_query($conexion, $sql_reservas);
        if ($reservas_result) {
            $reservas = mysqli_fetch_all($reservas_result, MYSQLI_ASSOC);
        }
        
        if (count($reservas) > 0) {
            $cantidad_personas = $reservas[0]['cantidad'];
            $sql_mesas = "SELECT * FROM mesa 
                         WHERE estado = 'disponible' 
                         AND capacidad >= $cantidad_personas
                         ORDER BY capacidad ASC";
            $mesas_result = mysqli_query($conexion, $sql_mesas);
            if ($mesas_result) {
                $mesas_disponibles = mysqli_fetch_all($mesas_result, MYSQLI_ASSOC);
            }
        }
    } else {
        $error = "No se encontró ningún cliente con el email: $email";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_mesa'])) {
    $reserva_id = $_POST['reserva_id'];
    $mesa_id = $_POST['mesa_id'];
    $cliente_id = $_POST['cliente_id'];
    

    $sql_actualizar_reserva = "UPDATE reserva SET estado = 'confirmada' WHERE `id reserva` = $reserva_id";
    mysqli_query($conexion, $sql_actualizar_reserva);
    

    $sql_asignar_mesa = "UPDATE mesa 
                        SET estado = 'ocupada', 
                            `cliente_id cliente` = $cliente_id,
                            `cliente_usuario_id usuario` = $cliente_id,
                            `fecha de asignacion` = CURDATE()
                        WHERE `id mesa` = $mesa_id";
    
    if (mysqli_query($conexion, $sql_asignar_mesa)) {
        $mensaje = "Mesa asignada correctamente al cliente";
 
        $sql_visita = "INSERT INTO `registro de visita` (`fecha hora`, `cantidad`, `tipo`) 
                      VALUES (NOW(), 1, 'cliente_reserva')";
        mysqli_query($conexion, $sql_visita);
    } else {
        $error = " Error al asignar mesa: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verificar Reserva y Asignar Mesa</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <style>
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .mensaje { background: #d4edda; color: #155724; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .form-group { margin: 20px 0; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, select { 
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
        .btn-danger { background: #e74c3c; }
    </style>
</head>
<body>
    <main class="principal">
        <header class="menu">
            <nav>
                <ul>
                    <li><a href="inicio.php">Inicio</a></li>
                    <li><a href="zona_staff.php">Volver a Mozos</a></li>
                    <?php if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador']): ?>
                        <li><a href="administracion.php">Panel Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <section class="contenido">
            <div class="container">
                <h1>Verificar Reserva y Asignar Mesa</h1>
                
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

              
                <form method="POST">
                    <div class="form-group">
                        <label> Email del cliente:</label>
                        <input type="email" name="email" required placeholder="Ingresa el email del cliente que hizo la reserva">
                    </div>
                    <button type="submit" name="buscar_cliente" class="btn"> Buscar Cliente</button>
                </form>

                <?php if ($cliente): ?>
                  
                    <div style="background: #e8f4f8; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h2>👤 Cliente: <?php echo $cliente['nombre']; ?></h2>
                        <p><strong>Email:</strong> <?php echo $cliente['gmail']; ?></p>
                        <p><strong>ID Cliente:</strong> <?php echo $cliente['id cliente']; ?></p>
                    </div>
                    
                    <?php if (count($reservas) > 0): ?>
            
                        <h3>Reservas Pendientes para Hoy:</h3>
                        <table>
                            <tr>
                                <th>ID Reserva</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Personas</th>
                                <th>Asignar Mesa</th>
                            </tr>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><strong>#<?php echo $reserva['id reserva']; ?></strong></td>
                                    <td><?php echo $reserva['fecha']; ?></td>
                                    <td><?php echo $reserva['hora de inicio']; ?></td>
                                    <td>
                                        <span style="background: #3498db; color: white; padding: 5px 10px; border-radius: 15px;">
                                            👥 <?php echo $reserva['cantidad']; ?> personas
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (count($mesas_disponibles) > 0): ?>
                                            <form method="POST">
                                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id reserva']; ?>">
                                                <input type="hidden" name="cliente_id" value="<?php echo $cliente['id cliente']; ?>">
                                                <select name="mesa_id" required style="padding: 8px; margin-right: 10px;">
                                                    <option value="">Seleccionar mesa...</option>
                                                    <?php foreach ($mesas_disponibles as $mesa): ?>
                                                        <option value="<?php echo $mesa['id mesa']; ?>">
                                                            🪑 Mesa <?php echo $mesa['numero']; ?> 
                                                            (Capacidad: <?php echo $mesa['capacidad']; ?> personas)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" name="asignar_mesa" class="btn btn-success">✅ Asignar Mesa</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #e74c3c;">❌ No hay mesas disponibles para <?php echo $reserva['cantidad']; ?> personas</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <div style="background: #fff3cd; padding: 15px; border-radius: 6px;">
                            <p>ℹNo hay reservas pendientes para hoy.</p>
                        </div>
                    <?php endif; ?>

             
                    <?php if (count($mesas_disponibles) > 0): ?>
                        <h3> Mesas Disponibles:</h3>
                        <table>
                            <tr>
                                <th>Número</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                            </tr>
                            <?php foreach ($mesas_disponibles as $mesa): ?>
                                <tr>
                                    <td>Mesa <?php echo $mesa['numero']; ?></td>
                                    <td><?php echo $mesa['capacidad']; ?> personas</td>
                                    <td><span style="color: #27ae60;">Disponible</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>

                <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !$cliente): ?>
                    <div style="background: #f8d7da; padding: 15px; border-radius: 6px;">
                        <p>No se encontró ningún cliente con ese email. Verifica que el cliente esté registrado en el sistema.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>