<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_mesa'])) {
    $numero = $_POST['numero'];
    $capacidad = $_POST['capacidad'];
    
    $sql_verificar = "SELECT * FROM mesa WHERE numero = $numero";
    $resultado = mysqli_query($conexion, $sql_verificar);
    
    if (mysqli_num_rows($resultado) > 0) {
        $error = "Ya existe una mesa con el número $numero";
    } else {
        $sql = "INSERT INTO mesa (numero, capacidad, estado, `fecha de asignacion`) 
                VALUES ($numero, $capacidad, 'disponible', CURDATE())";
        
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Mesa $numero creada correctamente (Capacidad: $capacidad personas)";
        } else {
            $error = "Error al crear mesa: " . mysqli_error($conexion);
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    
    $sql_verificar = "SELECT * FROM mesa WHERE `id mesa` = $mesa_id AND estado != 'disponible'";
    $resultado = mysqli_query($conexion, $sql_verificar);
    
    if (mysqli_num_rows($resultado) > 0) {
        $error = " No se puede eliminar la mesa porque está en uso";
    } else {
        $sql = "DELETE FROM mesa WHERE `id mesa` = $mesa_id";
        
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Mesa eliminada correctamente";
        } else {
            $error = "Error al eliminar mesa: " . mysqli_error($conexion);
        }
    }
}

$mesas = mysqli_query($conexion, "SELECT * FROM mesa ORDER BY numero");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <style>
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .mensaje { background: #d4edda; color: #155724; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, select { 
            padding: 12px; 
            width: 100%; 
            max-width: 300px; 
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
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
            text-align: center; 
        }
        th { 
            background: #34495e; 
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background: #f8f9fa; }
        .btn { 
            padding: 12px 25px; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .btn-success { background: #27ae60; }
        .btn-danger { background: #e74c3c; }
        .btn-info { background: #3498db; }
        
        .estado-disponible { color: #27ae60; font-weight: bold; }
        .estado-ocupada { color: #e74c3c; font-weight: bold; }
        .estado-reservada { color: #f39c12; font-weight: bold; }
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
                <h1> Gestión de Mesas</h1>

                <?php if (isset($mensaje)): ?>
                    <div class="mensaje"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

               
                <div class="card">
                    <h2> Crear Nueva Mesa</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Número de Mesa:</label>
                            <input type="number" name="numero" min="1" required placeholder="Ej: 1, 2, 3...">
                        </div>
                        
                        <div class="form-group">
                            <label>Capacidad (personas):</label>
                            <input type="number" name="capacidad" min="1" max="20" required placeholder="Ej: 2, 4, 6...">
                        </div>
                        
                        <button type="submit" name="crear_mesa" class="btn btn-success">Crear Mesa</button>
                    </form>
                </div>

              
                <div class="card">
                    <h2>Mesas del Restaurante</h2>
                    
                    <?php if (mysqli_num_rows($mesas) > 0): ?>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                            <?php while($mesa = mysqli_fetch_assoc($mesas)): 
                                $clase_estado = 'estado-' . $mesa['estado'];
                            ?>
                                <tr>
                                    <td>#<?php echo $mesa['id mesa']; ?></td>
                                    <td><strong>Mesa <?php echo $mesa['numero']; ?></strong></td>
                                    <td>👥 <?php echo $mesa['capacidad']; ?> personas</td>
                                    <td class="<?php echo $clase_estado; ?>">
                                        <?php 
                                        $icono = '';
                                        switch($mesa['estado']) {
                                            case 'disponible': $icono = '✅'; break;
                                            case 'ocupada': $icono = '🟡'; break;
                                            case 'reservada': $icono = '🔵'; break;
                                        }
                                        echo $icono . ' ' . ucfirst($mesa['estado']);
                                        ?>
                                    </td>
                                    <td><?php echo $mesa['fecha de asignacion']; ?></td>
                                    <td>
                                        <?php if ($mesa['estado'] == 'disponible'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="mesa_id" value="<?php echo $mesa['id mesa']; ?>">
                                                <button type="submit" name="eliminar_mesa" class="btn btn-danger" 
                                                        onclick="return confirm('¿Estás seguro de eliminar la Mesa <?php echo $mesa['numero']; ?>?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #7f8c8d;">No se puede eliminar (en uso)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else: ?>
                        <div style="text-align: center; padding: 30px; color: #7f8c8d;">
                            <p>No hay mesas creadas aún.</p>
                            <p>Crea la primera mesa usando el formulario superior.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2>Estadísticas de Mesas</h2>
                    <?php
                
                    $sql_stats = "SELECT 
                        COUNT(*) as total_mesas,
                        SUM(CASE WHEN estado = 'disponible' THEN 1 ELSE 0 END) as disponibles,
                        SUM(CASE WHEN estado = 'ocupada' THEN 1 ELSE 0 END) as ocupadas,
                        SUM(CASE WHEN estado = 'reservada' THEN 1 ELSE 0 END) as reservadas,
                        SUM(capacidad) as capacidad_total
                    FROM mesa";
                    
                    $stats = mysqli_fetch_assoc(mysqli_query($conexion, $sql_stats));
                    ?>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div style="text-align: center; padding: 15px; background: #e8f4f8; border-radius: 8px;">
                            <div style="font-size: 2em; font-weight: bold;"><?php echo $stats['total_mesas']; ?></div>
                            <div>Total Mesas</div>
                        </div>
                        
                        <div style="text-align: center; padding: 15px; background: #d4edda; border-radius: 8px;">
                            <div style="font-size: 2em; font-weight: bold; color: #27ae60;"><?php echo $stats['disponibles']; ?></div>
                            <div>Disponibles</div>
                        </div>
                        
                        <div style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 8px;">
                            <div style="font-size: 2em; font-weight: bold; color: #f39c12;"><?php echo $stats['reservadas']; ?></div>
                            <div>Reservadas</div>
                        </div>
                        
                        <div style="text-align: center; padding: 15px; background: #f8d7da; border-radius: 8px;">
                            <div style="font-size: 2em; font-weight: bold; color: #e74c3c;"><?php echo $stats['ocupadas']; ?></div>
                            <div>Ocupadas</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <strong>Capacidad total del restaurante:</strong> <?php echo $stats['capacidad_total']; ?> personas
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>