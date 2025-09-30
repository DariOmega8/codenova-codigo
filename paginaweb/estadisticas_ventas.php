<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}


$fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-01');
$fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');
$tipo_reporte = $_POST['tipo_reporte'] ?? 'diario';


$sql_ventas_totales = "
    SELECT 
        COUNT(*) as total_pedidos,
        SUM(pl.precio) as total_ventas,
        AVG(pl.precio) as promedio_venta
    FROM pedido p
    JOIN pedido_has_platos pp ON p.`id pedido` = pp.`pedido_id pedido`
    JOIN platos pl ON pp.`platos_id platos` = pl.`id platos`
    WHERE p.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
    AND p.estado = 'entregado'
";

$estadisticas = mysqli_fetch_assoc(mysqli_query($conexion, $sql_ventas_totales));


if ($tipo_reporte == 'diario') {
    $sql_ventas_periodo = "
        SELECT 
            p.fecha,
            COUNT(DISTINCT p.`id pedido`) as total_pedidos,
            SUM(pl.precio) as total_ventas
        FROM pedido p
        JOIN pedido_has_platos pp ON p.`id pedido` = pp.`pedido_id pedido`
        JOIN platos pl ON pp.`platos_id platos` = pl.`id platos`
        WHERE p.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
        AND p.estado = 'entregado'
        GROUP BY p.fecha
        ORDER BY p.fecha DESC
    ";
} else {
    $sql_ventas_periodo = "
        SELECT 
            YEAR(p.fecha) as año,
            MONTH(p.fecha) as mes,
            COUNT(DISTINCT p.`id pedido`) as total_pedidos,
            SUM(pl.precio) as total_ventas
        FROM pedido p
        JOIN pedido_has_platos pp ON p.`id pedido` = pp.`pedido_id pedido`
        JOIN platos pl ON pp.`platos_id platos` = pl.`id platos`
        WHERE p.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
        AND p.estado = 'entregado'
        GROUP BY YEAR(p.fecha), MONTH(p.fecha)
        ORDER BY año DESC, mes DESC
    ";
}

$ventas_periodo = mysqli_query($conexion, $sql_ventas_periodo);


$sql_platos_populares = "
    SELECT 
        pl.nombre,
        pl.precio,
        COUNT(*) as veces_vendido,
        SUM(pl.precio) as total_ventas
    FROM platos pl
    JOIN pedido_has_platos pp ON pl.`id platos` = pp.`platos_id platos`
    JOIN pedido p ON pp.`pedido_id pedido` = p.`id pedido`
    WHERE p.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
    AND p.estado = 'entregado'
    GROUP BY pl.`id platos`
    ORDER BY veces_vendido DESC
    LIMIT 10
";

$platos_populares = mysqli_query($conexion, $sql_platos_populares);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas de Ventas</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px; 
            margin: 20px 0; 
        }
        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 5px solid #e74c3c;
        }
        .stat-number { 
            font-size: 2em; 
            font-weight: bold; 
            color: #2c3e50;
            margin: 10px 0;
        }
        .filters { 
            background: #ecf0f1; 
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0;
        }
        .filter-group { 
            display: inline-block; 
            margin: 0 15px 15px 0; 
        }
        .chart-container { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background: #34495e; 
            color: white;
        }
        .btn { 
            padding: 10px 20px; 
            background: #3498db; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
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
                <h1>Estadísticas de Ventas</h1>

               
                <div class="filters">
                    <form method="POST">
                        <div class="filter-group">
                            <label>Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                        </div>
                        <div class="filter-group">
                            <label>Fecha Fin:</label>
                            <input type="date" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                        </div>
                        <div class="filter-group">
                            <label>Tipo Reporte:</label>
                            <select name="tipo_reporte">
                                <option value="diario" <?php echo $tipo_reporte=='diario'?'selected':''; ?>>Diario</option>
                                <option value="mensual" <?php echo $tipo_reporte=='mensual'?'selected':''; ?>>Mensual</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <br>
                            <button type="submit" class="btn"> Generar Reporte</button>
                        </div>
                    </form>
                </div>

             
                <div class="stats-grid">
                    <div class="stat-card">
                        <div> Total Pedidos</div>
                        <div class="stat-number"><?php echo $estadisticas['total_pedidos'] ?? 0; ?></div>
                        <div>Período seleccionado</div>
                    </div>
                    <div class="stat-card">
                        <div> Total Ventas</div>
                        <div class="stat-number">$<?php echo number_format($estadisticas['total_ventas'] ?? 0, 2); ?></div>
                        <div>Ingresos totales</div>
                    </div>
                    <div class="stat-card">
                        <div> Promedio por Pedido</div>
                        <div class="stat-number">$<?php echo number_format($estadisticas['promedio_venta'] ?? 0, 2); ?></div>
                        <div>Ticket promedio</div>
                    </div>
                </div>

               
                <div class="chart-container">
                    <h2> Evolución de Ventas</h2>
                    <canvas id="ventasChart" width="400" height="200"></canvas>
                </div>

              
                <div class="chart-container">
                    <h2>Detalle de Ventas por <?php echo $tipo_reporte == 'diario' ? 'Día' : 'Mes'; ?></h2>
                    <table>
                        <tr>
                            <th><?php echo $tipo_reporte == 'diario' ? 'Fecha' : 'Mes/Año'; ?></th>
                            <th>Total Pedidos</th>
                            <th>Total Ventas</th>
                            <th>Promedio por Pedido</th>
                        </tr>
                        <?php while($venta = mysqli_fetch_assoc($ventas_periodo)): ?>
                            <tr>
                                <td>
                                    <?php 
                                    if ($tipo_reporte == 'diario') {
                                        echo $venta['fecha'];
                                    } else {
                                        echo date('F Y', mktime(0, 0, 0, $venta['mes'], 1, $venta['año']));
                                    }
                                    ?>
                                </td>
                                <td><?php echo $venta['total_pedidos']; ?></td>
                                <td>$<?php echo number_format($venta['total_ventas'], 2); ?></td>
                                <td>$<?php echo number_format($venta['total_ventas'] / max($venta['total_pedidos'], 1), 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>

               
                <div class="chart-container">
                    <h2> Platos Más Populares</h2>
                    <table>
                        <tr>
                            <th>Plato</th>
                            <th>Precio</th>
                            <th>Veces Vendido</th>
                            <th>Total Generado</th>
                            <th>Popularidad</th>
                        </tr>
                        <?php while($plato = mysqli_fetch_assoc($platos_populares)): 
                            $popularidad = min(100, ($plato['veces_vendido'] / max($estadisticas['total_pedidos'], 1)) * 100);
                        ?>
                            <tr>
                                <td><strong><?php echo $plato['nombre']; ?></strong></td>
                                <td>$<?php echo number_format($plato['precio'], 2); ?></td>
                                <td><?php echo $plato['veces_vendido']; ?></td>
                                <td>$<?php echo number_format($plato['total_ventas'], 2); ?></td>
                                <td>
                                    <div style="background: #3498db; height: 20px; width: <?php echo $popularidad; ?>%;border-radius: 10px; color: white; text-align: center; font-size: 12px;">
                                        <?php echo number_format($popularidad, 1); ?>%
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script>
 
        const fechas = [
            <?php 
            mysqli_data_seek($ventas_periodo, 0);
            while($venta = mysqli_fetch_assoc($ventas_periodo)) {
                if ($tipo_reporte == 'diario') {
                    echo "'" . $venta['fecha'] . "',";
                } else {
                    echo "'" . date('M Y', mktime(0, 0, 0, $venta['mes'], 1, $venta['año'])) . "',";
                }
            }
            ?>
        ].reverse();
        
        const ventas = [
            <?php 
            mysqli_data_seek($ventas_periodo, 0);
            while($venta = mysqli_fetch_assoc($ventas_periodo)) {
                echo $venta['total_ventas'] . ",";
            }
            ?>
        ].reverse();

       
        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Ventas ($)',
                    data: ventas,
                    backgroundColor: 'rgba(231, 76, 60, 0.8)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto en Dólares ($)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>