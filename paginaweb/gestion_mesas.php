<?php
session_start(); // Inicia la sesión para manejar control de acceso
include "conexion.php"; // Incluye la conexión a la base de datos

// Verifica si el usuario es administrador, si no lo es lo redirige
if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

// Acción para crear una mesa cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_mesa'])) {
    $numero = $_POST['numero']; // Número de mesa ingresado
    $capacidad = $_POST['capacidad']; // Capacidad de la mesa
    
    // Verifica si ya existe una mesa con el mismo número
    $sql_verificar = "SELECT * FROM mesa WHERE numero = $numero";
    $resultado = mysqli_query($conexion, $sql_verificar);
    
    // Si ya existe una mesa con ese número muestra error
    if (mysqli_num_rows($resultado) > 0) {
        $error = "Ya existe una mesa con el número $numero";
    } else {
        // Inserta una nueva mesa con estado "disponible" y fecha actual
        $sql = "INSERT INTO mesa (numero, capacidad, estado, fecha_asig) 
                VALUES ($numero, $capacidad, 'disponible', CURDATE())";
        
        // Ejecuta la inserción
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Mesa $numero creada correctamente (Capacidad: $capacidad personas)";
        } else {
            $error = "Error al crear mesa: " . mysqli_error($conexion);
        }
    }
}

// Acción para eliminar una mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_mesa'])) {
    $mesa_id = $_POST['mesa_id']; // ID de la mesa a eliminar
    
    // Verifica si la mesa está en uso (estado distinto a disponible)
    $sql_verificar = "SELECT * FROM mesa WHERE mesa_id = $mesa_id AND estado != 'disponible'";
    $resultado = mysqli_query($conexion, $sql_verificar);
    
    // Si la mesa no está disponible, no puede eliminarse
    if (mysqli_num_rows($resultado) > 0) {
        $error = "No se puede eliminar la mesa porque está en uso";
    } else {
        // Elimina la mesa
        $sql = "DELETE FROM mesa WHERE mesa_id = $mesa_id";
        
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Mesa eliminada correctamente";
        } else {
            $error = "Error al eliminar mesa: " . mysqli_error($conexion);
        }
    }
}

// Obtiene todas las mesas para mostrarlas en la tabla
$mesas = mysqli_query($conexion, "SELECT * FROM mesa ORDER BY numero");
?>

<html>
<head>
    <title>Gestión de Mesas</title>
    <!-- Estilos generales -->
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
     <div class="contenedor-principal">
        <!-- Barra de navegación superior -->
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

        <div class="contenido-con-sidebar">
            <!-- Sidebar con enlaces de navegación interna -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#crear-mesa"><i class="fas fa-plus-circle"></i><span>Crear Mesa</span></a></li>
                    <li><a href="#listado-mesas"><i class="fas fa-list"></i><span>Listado de Mesas</span></a></li>
                    <li><a href="#estadisticas-mesas"><i class="fas fa-chart-pie"></i><span>Estadísticas</span></a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Gestión de Mesas</h1>
                </section>

                <!-- Mensajes de éxito o error -->
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje-exito"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="mensaje-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Formulario para crear una mesa -->
                <section id="crear-mesa" class="seccion-admin">
                    <h2>Crear Nueva Mesa</h2>
                    <div class="formulario-container">
                        <div class="formulario-seccion">
                            <form method="POST" class="formulario-admin">
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Número de Mesa:</label>
                                        <input type="number" name="numero" min="1" required>
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Capacidad (personas):</label>
                                        <input type="number" name="capacidad" min="1" max="20" required>
                                    </div>
                                </div>
                                <button type="submit" name="crear_mesa" class="btn-admin">Crear Mesa</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Tabla con listado de mesas -->
                <section id="listado-mesas" class="seccion-admin">
                    <h2>Mesas del Restaurante</h2>

                    <?php if (mysqli_num_rows($mesas) > 0): ?>
                        <div class="tabla-container">
                            <table class="tabla-admin">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Número</th>
                                        <th>Capacidad</th>
                                        <th>Estado</th>
                                        <th>Fecha Asignación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Recorre todas las mesas para mostrarlas
                                    mysqli_data_seek($mesas, 0);
                                    while($mesa = mysqli_fetch_assoc($mesas)): 
                                        $clase_estado = 'estado-' . $mesa['estado']; // Clase CSS según estado
                                    ?>
                                        <tr>
                                            <td>#<?php echo $mesa['mesa_id']; ?></td>
                                            <td><strong>Mesa <?php echo $mesa['numero']; ?></strong></td>
                                            <td>👥 <?php echo $mesa['capacidad']; ?> personas</td>

                                            <!-- Muestra el estado con icono -->
                                            <td class="<?php echo $clase_estado; ?>">
                                                <?php 
                                                $icono = '';
                                                switch($mesa['estado']) {
                                                    case 'disponible': $icono = '✅'; break;
                                                    case 'ocupada': $icono = '🟡'; break;
                                                    case 'reservada': $icono = '🔵'; break;
                                                    case 'mantenimiento': $icono = '🔴'; break;
                                                }
                                                echo $icono . ' ' . ucfirst($mesa['estado']);
                                                ?>
                                            </td>

                                            <td><?php echo $mesa['fecha_asig']; ?></td>
                                            <td>
                                                <!-- Solo se puede eliminar si está disponible -->
                                                <?php if ($mesa['estado'] == 'disponible'): ?>
                                                    <form method="POST" class="form-acciones">
                                                        <input type="hidden" name="mesa_id" value="<?php echo $mesa['mesa_id']; ?>">
                                                        <button type="submit" name="eliminar_mesa" class="btn-eliminar" 
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
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d;">No hay mesas creadas aún.</p>
                    <?php endif; ?>
                </section>

                <!-- Sección de estadísticas -->
                <section id="estadisticas-mesas" class="seccion-admin">
                    <h2>Estadísticas de Mesas</h2>

                    <?php
                    // Consulta que obtiene conteos según estado y capacidad total
                    $sql_stats = "SELECT 
                        COUNT(*) as total_mesas,
                        SUM(CASE WHEN estado = 'disponible' THEN 1 ELSE 0 END) as disponibles,
                        SUM(CASE WHEN estado = 'ocupada' THEN 1 ELSE 0 END) as ocupadas,
                        SUM(CASE WHEN estado = 'reservada' THEN 1 ELSE 0 END) as reservadas,
                        SUM(CASE WHEN estado = 'mantenimiento' THEN 1 ELSE 0 END) as mantenimiento,
                        SUM(capacidad) as capacidad_total
                    FROM mesa";
                    $result_stats = mysqli_query($conexion, $sql_stats);
                    $stats = mysqli_fetch_assoc($result_stats);
                    ?>
                    
                    <div class="stats-container">
                        <div class="stat-card"><h3>Total Mesas</h3><div class="stat-number"><?php echo $stats['total_mesas']; ?></div></div>
                        <div class="stat-card"><h3>Disponibles</h3><div class="stat-number" style="color: #27ae60;"><?php echo $stats['disponibles']; ?></div></div>
                        <div class="stat-card"><h3>Reservadas</h3><div class="stat-number" style="color: #f39c12;"><?php echo $stats['reservadas']; ?></div></div>
                        <div class="stat-card"><h3>Ocupadas</h3><div class="stat-number" style="color: #e74c3c;"><?php echo $stats['ocupadas']; ?></div></div>
                        <div class="stat-card"><h3>Mantenimiento</h3><div class="stat-number" style="color: #95a5a6;"><?php echo $stats['mantenimiento']; ?></div></div>
                    </div>

                    <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <strong>Capacidad total del restaurante:</strong> <?php echo $stats['capacidad_total']; ?> personas
                    </div>
                </section>
            </main>
        </div>

    <!-- Pie de página -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - PANEL ADMINISTRATIVO</div>
      <div class="footer-buttons">
        <a href="inicio.php">Volver al Inicio</a>
        <a href="cerrar_sesion.php">Cerrar Sesión</a>
      </div>
    </footer>

    <!-- Script para navegación suave entre secciones -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sidebarLinks = document.querySelectorAll('.sidebar a');
            const sections = document.querySelectorAll('.seccion-admin');
            
            // Muestra solo la primera sección al cargar
            if (sections.length > 0) {
                sections.forEach((section, index) => {
                    section.style.display = index === 0 ? 'block' : 'none';
                });
            }
            
            // Cambio de sección al hacer clic
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        sections.forEach(section => {
                            section.style.display = 'none';
                        });
                        targetSection.style.display = 'block';
                        targetSection.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

        });
    </script>
</body>
</html>
