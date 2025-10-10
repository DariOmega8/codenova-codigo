<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}


$sql_visitas_totales = "SELECT COUNT(*) as total, SUM(cantidad) as total_personas FROM `registro de visita`";
$resultado_total = mysqli_query($conexion, $sql_visitas_totales);
$visitas_totales = mysqli_fetch_assoc($resultado_total);
$total_visitas = $visitas_totales['total'];
$total_personas = $visitas_totales['total_personas'];

$sql_visitas_mes = "SELECT 
    YEAR(`fecha hora`) as año,
    MONTH(`fecha hora`) as mes,
    COUNT(*) as total_visitas,
    SUM(cantidad) as total_personas
    FROM `registro de visita` 
    GROUP BY YEAR(`fecha hora`), MONTH(`fecha hora`)
    ORDER BY año DESC, mes DESC
    LIMIT 12";

$visitas_mensuales = mysqli_query($conexion, $sql_visitas_mes);

$hoy = date('Y-m-d');
$sql_visitas_hoy = "SELECT COUNT(*) as hoy, SUM(cantidad) as personas_hoy 
                   FROM `registro de visita` 
                   WHERE DATE(`fecha hora`) = '$hoy'";
$resultado_hoy = mysqli_query($conexion, $sql_visitas_hoy);
$visitas_hoy_data = mysqli_fetch_assoc($resultado_hoy);
$visitas_hoy = $visitas_hoy_data['hoy'];
$personas_hoy = $visitas_hoy_data['personas_hoy'];

$sql_registrar_visita = "INSERT INTO `registro de visita` (`fecha hora`, `cantidad`) 
                        VALUES (NOW(), 1)";
mysqli_query($conexion, $sql_registrar_visita);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas de Visitas</title>
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
            <!-- Sidebar de estadísticas de visitas -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#resumen-visitas">
                        <i class="fas fa-chart-bar"></i>
                        <span>Resumen de Visitas</span>
                    </a></li>
                    <li><a href="#evolucion-visitas">
                        <i class="fas fa-chart-line"></i>
                        <span>Evolución de Visitas</span>
                    </a></li>
                    <li><a href="#detalle-mensual">
                        <i class="fas fa-table"></i>
                        <span>Detalle Mensual</span>
                    </a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Estadísticas de Visitas</h1>
                </section>

                <!-- Resumen de Visitas -->
                <section id="resumen-visitas" class="seccion-admin">
                    <h2>Resumen de Visitas</h2>
                    <div class="stats-container">
                        <div class="stat-card">
                            <h3>Total de Visitas</h3>
                            <div class="stat-number"><?php echo number_format($total_visitas); ?></div>
                            <p><?php echo number_format($total_personas); ?> personas total</p>
                        </div>
                        <div class="stat-card">
                            <h3>Visitas Hoy</h3>
                            <div class="stat-number"><?php echo $visitas_hoy; ?></div>
                            <p><?php echo $personas_hoy; ?> personas hoy</p>
                        </div>
                        <div class="stat-card">
                            <h3>Promedio Diario</h3>
                            <div class="stat-number">
                            <?php 
                           
                            $sql_primer_dia = "SELECT MIN(DATE(`fecha hora`)) as primer_dia FROM `registro de visita`";
                            $resultado_primer_dia = mysqli_query($conexion, $sql_primer_dia);
                            $primer_dia_data = mysqli_fetch_assoc($resultado_primer_dia);
                            $primer_dia = new DateTime($primer_dia_data['primer_dia']);
                            $hoy_obj = new DateTime();
                            $diferencia = $primer_dia->diff($hoy_obj);
                            $dias_totales = max($diferencia->days, 1);
                            
                            echo number_format($total_visitas / $dias_totales, 1); 
                            ?>
                        </div>
                            <p>En <?php echo $dias_totales; ?> días</p>
                        </div>
                </div>
           </section>

                <!-- Evolución de Visitas -->
                <section id="evolucion-visitas" class="seccion-admin">
                    <h2>Evolución de Visitas</h2>
                    <div class="chart-container">
                        <canvas id="visitasChart" width="400" height="200"></canvas>
                    </div>
                </section>

                <!-- Detalle Mensual -->
                <section id="detalle-mensual" class="seccion-admin">
                    <h2>Detalle Mensual</h2>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>Mes/Año</th>
                                    <th>Total Visitas</th>
                                    <th>Total Personas</th>
                                    <th>Promedio Diario</th>
                                    <th>Tendencia</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php 
                        $meses_anteriores = [];
                        while($mes = mysqli_fetch_assoc($visitas_mensuales)) {
                            $meses_anteriores[] = $mes;
                        }
                        
                        foreach($meses_anteriores as $index => $mes): 
                            $nombre_mes = date('F Y', mktime(0, 0, 0, $mes['mes'], 1, $mes['año']));
                            $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes['mes'], $mes['año']);
                            $promedio_diario = $mes['total_visitas'] / $dias_mes;
                            
                            $tendencia = '➡️';
                            if ($index < count($meses_anteriores) - 1) {
                                $mes_anterior = $meses_anteriores[$index + 1];
                                if ($mes['total_visitas'] > $mes_anterior['total_visitas']) {
                                    $tendencia = '📈';
                                } elseif ($mes['total_visitas'] < $mes_anterior['total_visitas']) {
                                    $tendencia = '📉';
                                }
                            }
                        ?>
                            <tr>
                                <td><?php echo $nombre_mes; ?></td>
                                <td><?php echo number_format($mes['total_visitas']); ?></td>
                                <td><?php echo number_format($mes['total_personas']); ?></td>
                                <td><?php echo number_format($promedio_diario, 1); ?></td>
                                <td><?php echo $tendencia; ?></td>
                            </tr>
                        <?php endforeach; ?>
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
     
        const meses = [
            <?php 
            foreach(array_reverse($meses_anteriores) as $mes) {
                echo "'" . date('M Y', mktime(0, 0, 0, $mes['mes'], 1, $mes['año'])) . "',";
            }
            ?>
        ];
        
        const visitas = [
            <?php 
            foreach(array_reverse($meses_anteriores) as $mes) {
                echo $mes['total_visitas'] . ",";
            }
            ?>
        ];

       
        const ctx = document.getElementById('visitasChart').getContext('2d');
        const visitasChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Visitas Mensuales',
                    data: visitas,
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Evolución de Visitas Mensuales'
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de Visitas'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>