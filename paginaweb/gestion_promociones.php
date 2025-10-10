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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Promociones - La Chacra Gourmet</title>
    <link rel="stylesheet" href="estilos/estilo_general.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/69a3421d9e.js" crossorigin="anonymous"></script>
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
            <!-- Sidebar de gestión de promociones -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#crear-promocion">
                        <i class="fas fa-plus-circle"></i>
                        <span>Crear Promoción</span>
                    </a></li>
                    <li><a href="#asignar-promocion">
                        <i class="fas fa-user-tag"></i>
                        <span>Asignar a Cliente</span>
                    </a></li>
                    <li><a href="#lista-promociones">
                        <i class="fas fa-list"></i>
                        <span>Lista de Promociones</span>
                    </a></li>
                    <li><a href="#promociones-asignadas">
                        <i class="fas fa-history"></i>
                        <span>Promociones Asignadas</span>
                    </a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Gestión de Promociones</h1>
                </section>

                <!-- Mensajes -->
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje-exito"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="mensaje-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Crear Nueva Promoción -->
                <section id="crear-promocion" class="seccion-admin">
                    <h2>Crear Nueva Promoción</h2>
                    <div class="formulario-container">
                        <div class="formulario-seccion">
                            <form method="POST" class="formulario-admin">
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Título de la Promoción:</label>
                                        <input type="text" name="titulo" required placeholder="Ej: 2x1 en Postres" maxlength="15">
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Duración (días):</label>
                                        <input type="number" name="duracion" required min="1" placeholder="30">
                                    </div>
                                </div>
                                
                                <div class="grupo-formulario">
                                    <label>Descripción:</label>
                                    <textarea name="descripcion" required placeholder="Describe los beneficios de la promoción" maxlength="50"></textarea>
                                </div>
                                
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Tipo de Promoción:</label>
                                        <select name="tipo" required>
                                            <option value="descuento">💰 Descuento</option>
                                            <option value="2x1">2️⃣✖️1️⃣ 2x1</option>
                                            <option value="combo">🍽️ Combo</option>
                                            <option value="regalo">🎁 Regalo</option>
                                            <option value="earlybird">🐦 Early Bird</option>
                                        </select>
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Condiciones:</label>
                                        <input type="text" name="condiciones" placeholder="Ej: Válido de lunes a viernes" maxlength="10">
                                    </div>
                                </div>
                                
                                <button type="submit" name="crear_promocion" class="btn-admin">Crear Promoción</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Asignar Promoción a Cliente -->
                <section id="asignar-promocion" class="seccion-admin">
                    <h2>Asignar Promoción a Cliente</h2>
                    <div class="formulario-container">
                        <div class="formulario-seccion">
                            <form method="POST" class="formulario-admin">
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
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
                                    <div class="grupo-formulario">
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
                                </div>
                                
                                <button type="submit" name="asignar_promocion" class="btn-admin">Asignar Promoción</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Lista de Promociones -->
                <section id="lista-promociones" class="seccion-admin">
                    <h2>Promociones del Sistema</h2>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
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
                            </thead>
                            <tbody>
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
                                            <form method="POST" class="form-acciones">
                                                <input type="hidden" name="promocion_id" value="<?php echo $promocion['id promocion']; ?>">
                                                <select name="estado" onchange="this.form.submit()">
                                                    <option value="activa" <?php echo $promocion['estado']=='activa'?'selected':''; ?>>Activar</option>
                                                    <option value="inactiva" <?php echo $promocion['estado']=='inactiva'?'selected':''; ?>>Desactivar</option>
                                                </select>
                                                <input type="hidden" name="cambiar_estado" value="1">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Promociones Asignadas -->
                <section id="promociones-asignadas" class="seccion-admin">
                    <h2>Promociones Asignadas a Clientes</h2>
                    <?php if (mysqli_num_rows($promociones_asignadas) > 0): ?>
                        <div class="tabla-container">
                            <table class="tabla-admin">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Promoción</th>
                                        <th>Tipo</th>
                                        <th>Fecha Asignación</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d; padding: 20px;">
                            No hay promociones asignadas a clientes aún.
                        </p>
                    <?php endif; ?>
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
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            const sections = document.querySelectorAll('.seccion-admin');
            
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
            
            if (sections.length > 0) {
                sections.forEach((section, index) => {
                    section.style.display = index === 0 ? 'block' : 'none';
                });
            }
        });
    </script>
</body>
</html>