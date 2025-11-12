<?php
// Iniciar sesión para acceder a las variables de sesión
session_start();

// Incluir archivo de conexión a la base de datos
include "conexion.php";

// Verificar si el usuario es administrador, si no, redirigir al inicio
if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

// Configurar fechas por defecto para el reporte
// Si no se especifican fechas, usar el primer día del mes actual hasta hoy
$fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-01');
$fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');
$tipo_reporte = $_POST['tipo_reporte'] ?? 'diario';

// Consulta para obtener estadísticas generales de ventas
// Solo considerar pedidos con estado 'entregado' (corregido de 'entregado')
$sql_ventas_totales = "
    SELECT 
        COUNT(*) as total_pedidos,
        COALESCE(SUM(pd.precio_total), 0) as total_ventas,
        COALESCE(AVG(pd.precio_total), 0) as promedio_venta
    FROM pedido p
    LEFT JOIN pedido_detalle pd ON p.pedido_id = pd.pedido_pedido_id
    WHERE DATE(p.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'
    AND p.estado = 'entregado'  -- Solo pedidos entregados
";

// Ejecutar consulta de ventas totales
$result_ventas = mysqli_query($conexion, $sql_ventas_totales);
if (!$result_ventas) {
    // Si hay error en la consulta, mostrar mensaje y establecer valores por defecto
    echo "Error en consulta: " . mysqli_error($conexion);
    $estadisticas = ['total_pedidos' => 0, 'total_ventas' => 0, 'promedio_venta' => 0];
} else {
    // Obtener resultados de la consulta
    $estadisticas = mysqli_fetch_assoc($result_ventas);
}

// Consulta para ventas por período (diario o mensual)
if ($tipo_reporte == 'diario') {
    // Agrupar ventas por día
    $sql_ventas_periodo = "
        SELECT 
            DATE(p.fecha_hora) as fecha,
            COUNT(DISTINCT p.pedido_id) as total_pedidos,
            COALESCE(SUM(pd.precio_total), 0) as total_ventas
        FROM pedido p
        LEFT JOIN pedido_detalle pd ON p.pedido_id = pd.pedido_pedido_id
        WHERE DATE(p.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        AND p.estado = 'entregado'
        GROUP BY DATE(p.fecha_hora)
        ORDER BY fecha DESC
    ";
} else {
    // Agrupar ventas por mes y año
    $sql_ventas_periodo = "
        SELECT 
            YEAR(p.fecha_hora) as año,
            MONTH(p.fecha_hora) as mes,
            COUNT(DISTINCT p.pedido_id) as total_pedidos,
            COALESCE(SUM(pd.precio_total), 0) as total_ventas
        FROM pedido p
        LEFT JOIN pedido_detalle pd ON p.pedido_id = pd.pedido_pedido_id
        WHERE DATE(p.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        AND p.estado = 'entregado'
        GROUP BY YEAR(p.fecha_hora), MONTH(p.fecha_hora)
        ORDER BY año DESC, mes DESC
    ";
}

// Ejecutar consulta de ventas por período
$ventas_periodo = mysqli_query($conexion, $sql_ventas_periodo);
if (!$ventas_periodo) {
    echo "Error en consulta de ventas por período: " . mysqli_error($conexion);
    $ventas_periodo = [];
}

// Consulta para obtener los platos más populares
$sql_platos_populares = "
    SELECT 
        pl.nombre,
        pl.precio,
        COALESCE(SUM(pd.cantidad), 0) as veces_vendido,
        COALESCE(SUM(pd.precio_total), 0) as total_ventas
    FROM plato pl
    LEFT JOIN pedido_detalle pd ON pl.plato_id = pd.plato_plato_id
    LEFT JOIN pedido p ON pd.pedido_pedido_id = p.pedido_id
    WHERE (p.fecha_hora IS NULL OR DATE(p.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin')
    AND (p.estado IS NULL OR p.estado = 'entregado')
    GROUP BY pl.plato_id, pl.nombre, pl.precio
    ORDER BY veces_vendido DESC, total_ventas DESC
    LIMIT 10
";

// Ejecutar consulta de platos populares
$platos_populares_result = mysqli_query($conexion, $sql_platos_populares);
if (!$platos_populares_result) {
    echo "Error en consulta de platos populares: " . mysqli_error($conexion);
    $platos_populares_data = [];
} else {
    // Procesar resultados de platos populares
    $platos_populares_data = [];
    $total_vendido = 0;
    while($plato = mysqli_fetch_assoc($platos_populares_result)) {
        $platos_populares_data[] = $plato;
        $total_vendido += $plato['veces_vendido'];
    }
}

// Registrar consultas para debugging (se puede comentar en producción)
error_log("SQL Ventas Totales: " . $sql_ventas_totales);
error_log("SQL Ventas Período: " . $sql_ventas_periodo);
error_log("SQL Platos Populares: " . $sql_platos_populares);
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
            <!-- Sidebar de navegación para estadísticas -->
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

            <!-- Contenido principal de estadísticas -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Estadísticas de Ventas</h1>
                    <!-- Información de debug sobre el período consultado -->
                    <div style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px;">
                        <small>Período: <?php echo $fecha_inicio . ' a ' . $fecha_fin; ?> | 
                        Estado pedidos: entregado | 
                        Total pedidos encontrados: <?php echo $estadisticas['total_pedidos']; ?></small>
                    </div>
                </section>

                <!-- Sección de filtros para el reporte -->
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

                <!-- Resumen general de ventas -->
                <section id="resumen-ventas" class="seccion-admin">
                    <h2>Resumen de Ventas</h2>
                    <?php if ($estadisticas['total_pedidos'] == 0): ?>
                        <div class="mensaje-info">
                            No se encontraron pedidos entregados en el período seleccionado.
                            <br><small>Verifique que los pedidos tengan estado "entregado" y estén dentro del rango de fechas.</small>
                        </div>
                    <?php endif; ?>
                    
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

                <!-- Gráfico de evolución de ventas -->
                <section id="evolucion-ventas" class="seccion-admin">
                    <h2>Evolución de Ventas</h2>
                    <?php 
                    // Verificar si hay datos para mostrar en el gráfico
                    mysqli_data_seek($ventas_periodo, 0);
                    $hay_datos_grafico = false;
                    while($venta = mysqli_fetch_assoc($ventas_periodo)) {
                        if ($venta['total_ventas'] > 0) {
                            $hay_datos_grafico = true;
                            break;
                        }
                    }
                    mysqli_data_seek($ventas_periodo, 0);
                    ?>
                    
                    <?php if (!$hay_datos_grafico): ?>
                        <div class="mensaje-info">No hay datos suficientes para mostrar el gráfico.</div>
                    <?php else: ?>
                        <div class="chart-container">
                            <canvas id="ventasChart" width="400" height="200"></canvas>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Tabla detallada de ventas -->
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
                                <?php 
                                // Reiniciar puntero de resultados para recorrer nuevamente
                                mysqli_data_seek($ventas_periodo, 0);
                                while($venta = mysqli_fetch_assoc($ventas_periodo)): 
                                ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            // Formatear fecha según el tipo de reporte
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

                <!-- Tabla de platos más populares -->
                <section id="platos-populares" class="seccion-admin">
                    <h2>Platos Más Populares</h2>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>Plato</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad Vendida</th>
                                    <th>Total Generado</th>
                                    <th>Popularidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($platos_populares_data)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No hay datos de platos vendidos</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($platos_populares_data as $plato): 
                                        // Calcular porcentaje de popularidad
                                        $popularidad = $total_vendido > 0 ? ($plato['veces_vendido'] / $total_vendido) * 100 : 0;
                                    ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($plato['nombre']); ?></strong></td>
                                            <td class="precio">$<?php echo number_format($plato['precio'], 2); ?></td>
                                            <td><?php echo $plato['veces_vendido']; ?></td>
                                            <td class="precio">$<?php echo number_format($plato['total_ventas'], 2); ?></td>
                                            <td>
                                                <!-- Barra de progreso para mostrar popularidad -->
                                                <div style="background: #3498db; height: 20px; width: <?php echo $popularidad; ?>%; border-radius: 10px; color: white; text-align: center; font-size: 12px; line-height: 20px;">
                                                    <?php echo number_format($popularidad, 1); ?>%
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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

    <!-- Script para generar gráfico de ventas -->
    <?php if ($hay_datos_grafico): ?>
    <script>
        // Reiniciar puntero de resultados para el gráfico
        <?php mysqli_data_seek($ventas_periodo, 0); ?>
        
        // Preparar datos para el gráfico - fechas
        const fechas = [
            <?php 
            while($venta = mysqli_fetch_assoc($ventas_periodo)) {
                if ($tipo_reporte == 'diario') {
                    echo "'" . $venta['fecha'] . "',";
                } else {
                    echo "'" . date('M Y', mktime(0, 0, 0, $venta['mes'], 1, $venta['año'])) . "',";
                }
            }
            ?>
        ].reverse(); // Invertir para mostrar de más antiguo a más reciente
        
        <?php mysqli_data_seek($ventas_periodo, 0); ?>
        // Preparar datos para el gráfico - montos de venta
        const ventas = [
            <?php 
            while($venta = mysqli_fetch_assoc($ventas_periodo)) {
                echo $venta['total_ventas'] . ",";
            }
            ?>
        ].reverse(); // Invertir para coincidir con las fechas

        // Crear gráfico con Chart.js
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
    <?php endif; ?>
</body>
</html>