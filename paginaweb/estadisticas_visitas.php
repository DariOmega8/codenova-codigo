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
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px; 
            margin: 20px 0; 
        }
        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 5px solid #3498db;
        }
        .stat-number { 
            font-size: 2.5em; 
            font-weight: bold; 
            color: #2c3e50;
            margin: 10px 0;
        }
        .stat-label { 
            color: #7f8c8d; 
            font-size: 1.1em;
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
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 15px; 
            text-align: left; 
        }
        th { 
            background: #34495e; 
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) { background: #f8f9fa; }
        .filters { 
            background: #ecf0f1; 
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0;
        }
        .filter-group { margin: 15px 0; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, select { 
            padding: 10px; 
            border: 2px solid #bdc3c7;
            border-radius: 5px;
            font-size: 16px;
        }
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
                <h1> Estadísticas de Visitas</h1>

             
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">📈 Total de Visitas</div>
                        <div class="stat-number"><?php echo number_format($total_visitas); ?></div>
                        <div style="color: #27ae60; font-size: 0.9em;"><?php echo number_format($total_personas); ?> personas total</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">📅 Visitas Hoy</div>
                        <div class="stat-number"><?php echo $visitas_hoy; ?></div>
                        <div style="color: #e74c3c; font-size: 0.9em;"><?php echo $personas_hoy; ?> personas hoy</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">👥 Promedio Diario</div>
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
                        <div style="color: #9b59b6; font-size: 0.9em;">En <?php echo $dias_totales; ?> días</div>
                    </div>
                </div>

               
                <div class="chart-container">
                    <h2>Visitas Mensuales</h2>
                    <canvas id="visitasChart" width="400" height="200"></canvas>
                </div>

             
                <div class="chart-container">
                    <h2>Detalle Mensual</h2>
                    <table>
                        <tr>
                            <th>Mes/Año</th>
                            <th>Total Visitas</th>
                            <th>Total Personas</th>
                            <th>Promedio Diario</th>
                            <th>Tendencia</th>
                        </tr>
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
                                <td><strong><?php echo $nombre_mes; ?></strong></td>
                                <td><?php echo $mes['total_visitas']; ?></td>
                                <td><?php echo $mes['total_personas']; ?></td>
                                <td><?php echo number_format($promedio_diario, 1); ?></td>
                                <td style="text-align: center;"><?php echo $tendencia; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </section>
    </main>

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