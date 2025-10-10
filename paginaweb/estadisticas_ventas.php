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
</head>
<body>
     <div class="contenedor-principal">
        <!-- Header superior -->
        <header class="menu">
            <div class="logo">
                <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
            </div>
            <nav class="navegacion-principal">
                <ul>
                    <li><a href="inicio.php">Inicio</a></li>
                    <li><a href="administracion.php">Panel Admin</a></li>
                    <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <!-- Contenido principal con sidebar -->
        <div class="contenido-con-sidebar">
            <!-- Sidebar de estadísticas de ventas -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#resumen-ventas">
                        <i class="fas fa-chart-bar"></i>
                        <span>Resumen de Ventas</span>
                    </a></li>
                    <li><a href="#evolucion-ventas">
                        <i class="fas fa-chart-line"></i>
                        <span>Evolución de Ventas</span>
                    </a></li>
                    <li><a href="#detalle-ventas">
                        <i class="fas fa-table"></i>
                        <span>Detalle de Ventas</span>
                    </a></li>
                    <li><a href="#platos-populares">
                        <i class="fas fa-utensils"></i>
                        <span>Platos Populares</span>
                    </a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Estadísticas de Ventas</h1>
                </section>

                <!-- Filtros -->
                <section class="seccion-admin">
                    <h2>Filtros del Reporte</h2>
                    <div class="formulario-container">
                        <div class="formulario-seccion">
                            <form method="POST" class="formulario-admin">
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Fecha Inicio:</label>
                                        <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Fecha Fin:</label>
                                        <input type="date" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Tipo Reporte:</label>
                                        <select name="tipo_reporte">
                                            <option value="diario" <?php echo $tipo_reporte=='diario'?'selected':''; ?>>Diario</option>
                                            <option value="mensual" <?php echo $tipo_reporte=='mensual'?'selected':''; ?>>Mensual</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn-admin">Generar Reporte</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Resumen de Ventas -->
                <section id="resumen-ventas" class="seccion-admin">
                    <h2>Resumen de Ventas</h2>
                    <div class="stats-container">
                        <div class="stat-card">
                            <h3>Total Pedidos</h3>
                            <div class="stat-number"><?php echo $estadisticas['total_pedidos'] ?? 0; ?></div>
                            <p>Período seleccionado</p>
                        </div>
                        <div class="stat-card">
                            <h3>Total Ventas</h3>
                            <div class="stat-number">$<?php echo number_format($estadisticas['total_ventas'] ?? 0, 2); ?></div>
                            <p>Ingresos totales</p>
                        </div>
                        <div class="stat-card">
                            <h3>Promedio por Pedido</h3>
                            <div class="stat-number">$<?php echo number_format($estadisticas['promedio_venta'] ?? 0, 2); ?></div>
                            <p>Ticket promedio</p>
                        </div>
                    </div>
                </section>

                <!-- Evolución de Ventas -->
                <section id="evolucion-ventas" class="seccion-admin">
                    <h2>Evolución de Ventas</h2>
                    <div class="chart-container">
                        <canvas id="ventasChart" width="400" height="200"></canvas>
                    </div>
                </section>

                <!-- Detalle de Ventas -->
                <section id="detalle-ventas" class="seccion-admin">
                    <h2>Detalle de Ventas por <?php echo $tipo_reporte == 'diario' ? 'Día' : 'Mes'; ?></h2>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th><?php echo $tipo_reporte == 'diario' ? 'Fecha' : 'Mes/Año'; ?></th>
                                    <th>Total Pedidos</th>
                                    <th>Total Ventas</th>
                                    <th>Promedio por Pedido</th>
                                </tr>
                            </thead>
                            <tbody>
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
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Platos Populares -->
                <section id="platos-populares" class="seccion-admin">
                    <h2>Platos Más Populares</h2>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>Plato</th>
                                    <th>Precio</th>
                                    <th>Veces Vendido</th>
                                    <th>Total Generado</th>
                                    <th>Popularidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($plato = mysqli_fetch_assoc($platos_populares)): 
                                    $popularidad = min(100, ($plato['veces_vendido'] / max($estadisticas['total_pedidos'], 1)) * 100);
                                ?>
                                    <tr>
                                        <td><strong><?php echo $plato['nombre']; ?></strong></td>
                                        <td class="precio">$<?php echo number_format($plato['precio'], 2); ?></td>
                                        <td><?php echo $plato['veces_vendido']; ?></td>
                                        <td class="precio">$<?php echo number_format($plato['total_ventas'], 2); ?></td>
                                        <td>
                                            <div style="background: #3498db; height: 20px; width: <?php echo $popularidad; ?>%;border-radius: 10px; color: white; text-align: center; font-size: 12px;">
                                                <?php echo number_format($popularidad, 1); ?>%
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>

         <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - PANEL ADMINISTRATIVO</div>
      <div class="footer-buttons">
        <a href="inicio.php">Volver al Inicio</a>
        <a href="cerrar_sesion.php">Cerrar Sesión</a>
      </div>
    </footer>
  </div>

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