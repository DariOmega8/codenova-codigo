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

<html>
<head>
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            <!-- Sidebar de gestión de mesas -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#crear-mesa">
                        <i class="fas fa-plus-circle"></i>
                        <span>Crear Mesa</span>
                    </a></li>
                    <li><a href="#listado-mesas">
                        <i class="fas fa-list"></i>
                        <span>Listado de Mesas</span>
                    </a></li>
                    <li><a href="#estadisticas-mesas">
                        <i class="fas fa-chart-pie"></i>
                        <span>Estadísticas</span>
                    </a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Gestión de Mesas</h1>
                </section>

                <!-- Mensajes -->
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje-exito"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="mensaje-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Crear Mesa -->
                <section id="crear-mesa" class="seccion-admin">
                    <h2>Crear Nueva Mesa</h2>
                    <div class="formulario-container">
                        <div class="formulario-seccion">
                            <form method="POST" class="formulario-admin">
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Número de Mesa:</label>
                                        <input type="number" name="numero" min="1" required placeholder="Ej: 1, 2, 3...">
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Capacidad (personas):</label>
                                        <input type="number" name="capacidad" min="1" max="20" required placeholder="Ej: 2, 4, 6...">
                                    </div>
                                </div>
                                <button type="submit" name="crear_mesa" class="btn-admin">Crear Mesa</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Listado de Mesas -->
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
                                    // Reiniciar el puntero del resultado para poder iterar nuevamente
                                    mysqli_data_seek($mesas, 0);
                                    while($mesa = mysqli_fetch_assoc($mesas)): 
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
                                                    <form method="POST" class="form-acciones">
                                                        <input type="hidden" name="mesa_id" value="<?php echo $mesa['id mesa']; ?>">
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
                        <p style="text-align: center; color: #7f8c8d; padding: 20px;">
                            No hay mesas creadas aún. Crea la primera mesa usando el formulario superior.
                        </p>
                    <?php endif; ?>
                </section>

                 <!-- Estadísticas de Mesas -->
                <section id="estadisticas-mesas" class="seccion-admin">
                    <h2>Estadísticas de Mesas</h2>
                    <?php
                    // Reiniciar consulta para estadísticas
                    $sql_stats = "SELECT 
                        COUNT(*) as total_mesas,
                        SUM(CASE WHEN estado = 'disponible' THEN 1 ELSE 0 END) as disponibles,
                        SUM(CASE WHEN estado = 'ocupada' THEN 1 ELSE 0 END) as ocupadas,
                        SUM(CASE WHEN estado = 'reservada' THEN 1 ELSE 0 END) as reservadas,
                        SUM(capacidad) as capacidad_total
                    FROM mesa";
                    
                    $result_stats = mysqli_query($conexion, $sql_stats);
                    $stats = mysqli_fetch_assoc($result_stats);
                    ?>
                    
                    <div class="stats-container">
                        <div class="stat-card">
                            <h3>Total Mesas</h3>
                            <div class="stat-number"><?php echo $stats['total_mesas']; ?></div>
                        </div>
                        <div class="stat-card">
                            <h3>Disponibles</h3>
                            <div class="stat-number" style="color: #27ae60;"><?php echo $stats['disponibles']; ?></div>
                        </div>
                        <div class="stat-card">
                            <h3>Reservadas</h3>
                            <div class="stat-number" style="color: #f39c12;"><?php echo $stats['reservadas']; ?></div>
                        </div>
                        <div class="stat-card">
                            <h3>Ocupadas</h3>
                            <div class="stat-number" style="color: #e74c3c;"><?php echo $stats['ocupadas']; ?></div>
                        </div>
                    </div>
                    <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <strong>Capacidad total del restaurante:</strong> <?php echo $stats['capacidad_total']; ?> personas
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <script>
        // Script para navegación suave entre secciones
        document.addEventListener('DOMContentLoaded', function() {
            // Navegación del sidebar
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            const sections = document.querySelectorAll('.seccion-admin');
            
            // Mostrar solo la primera sección al cargar
            if (sections.length > 0) {
                sections.forEach((section, index) => {
                    section.style.display = index === 0 ? 'block' : 'none';
                });
            }
            
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        // Ocultar todas las secciones
                        sections.forEach(section => {
                            section.style.display = 'none';
                        });
                        
                        // Mostrar la sección objetivo
                        targetSection.style.display = 'block';
                        
                        // Scroll suave
                        targetSection.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            // Manejo de imágenes
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    console.log('Imagen no encontrada:', this.src);
                });
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            });
        });
    </script>
</body>
</html>