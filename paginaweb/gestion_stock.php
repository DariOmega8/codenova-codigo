<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

// Obtener el admin_id del usuario actual
$admin_id = $_SESSION['admin_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_producto'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $unidad_medida = mysqli_real_escape_string($conexion, $_POST['unidad_medida']);
    
    // Primero insertar en stock
    $sql_stock = "INSERT INTO stock (unidad_medida, cantidad_total, ultima_actu) 
                 VALUES ('$unidad_medida', $cantidad, NOW())";
    
    if (mysqli_query($conexion, $sql_stock)) {
        $stock_id = mysqli_insert_id($conexion);
        
        // Luego insertar en producto con referencia al admin
        $sql_producto = "INSERT INTO producto (cantidad, nombre, precio, tipo, admin_admin_id, stock_stock_id) 
                        VALUES ($cantidad, '$nombre', $precio, '$tipo', $admin_id, $stock_id)";
        
        if (mysqli_query($conexion, $sql_producto)) {
            $mensaje = "Producto agregado correctamente al stock";
        } else {
            $error = "Error al agregar producto: " . mysqli_error($conexion);
            // Revertir la inserción en stock si falla producto
            mysqli_query($conexion, "DELETE FROM stock WHERE stock_id = $stock_id");
        }
    } else {
        $error = "Error al crear registro de stock: " . mysqli_error($conexion);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_stock'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad_movimiento = $_POST['cantidad_movimiento']; 
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    
    // Consulta actualizada para obtener información del producto
    $sql_info_producto = "SELECT p.cantidad, p.stock_stock_id 
                         FROM producto p 
                         WHERE p.producto_id = $producto_id";
    $resultado_info = mysqli_query($conexion, $sql_info_producto);
    
    if ($resultado_info && mysqli_num_rows($resultado_info) > 0) {
        $producto_info = mysqli_fetch_assoc($resultado_info);
        $cantidad_actual = $producto_info['cantidad'];
        $stock_id = $producto_info['stock_stock_id'];
        
        if ($tipo_movimiento == 'entrada') {
            $nueva_cantidad = $cantidad_actual + $cantidad_movimiento;
        } elseif ($tipo_movimiento == 'salida') {
            $nueva_cantidad = $cantidad_actual - $cantidad_movimiento;
        } else { 
            $nueva_cantidad = $cantidad_movimiento;
        }
        
        if ($nueva_cantidad < 0) {
            $error = "Error: La cantidad no puede ser negativa";
        } else {
            // Actualizar producto
            $sql_actualizar = "UPDATE producto SET cantidad = $nueva_cantidad WHERE producto_id = $producto_id";
            
            if (mysqli_query($conexion, $sql_actualizar)) {
                // Actualizar stock
                $sql_stock = "UPDATE stock 
                             SET cantidad_total = $nueva_cantidad, 
                                 ultima_actu = NOW() 
                             WHERE stock_id = $stock_id";
                mysqli_query($conexion, $sql_stock);
                
                // Insertar en historial (con referencia al admin)
                $sql_historial = "INSERT INTO historial (descripcion, tipo_movimiento, cantidad, fecha, `stock_id stock`, admin_admin_id) 
                                 VALUES ('$descripcion', '$tipo_movimiento', $cantidad_movimiento, NOW(), $stock_id, $admin_id)";
                mysqli_query($conexion, $sql_historial);
                
                $mensaje = "Stock actualizado correctamente (De $cantidad_actual a $nueva_cantidad)";
            } else {
                $error = "Error al actualizar stock: " . mysqli_error($conexion);
            }
        }
    } else {
        $error = "Producto no encontrado";
    }
}

// Consulta de productos actualizada
$productos = mysqli_query($conexion, "
    SELECT p.*, s.unidad_medida, s.ultima_actu
    FROM producto p 
    JOIN stock s ON p.stock_stock_id = s.stock_id
    ORDER BY p.tipo, p.nombre
");

// Estadísticas por tipo actualizada
$stats_tipos = mysqli_query($conexion, "
    SELECT tipo, COUNT(*) as total, SUM(cantidad) as cantidad_total
    FROM producto 
    GROUP BY tipo
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Stock - La Chacra Gourmet</title>
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

        <!-- Contenido principal -->
        <div class="contenido-con-sidebar">
            <!-- Sidebar de gestión de stock -->
            <aside class="sidebar">
                <ul>
                    <li><a href="#estadisticas-stock">
                        <i class="fas fa-chart-bar"></i>
                        <span>Estadísticas</span>
                    </a></li>
                    <li><a href="#agregar-producto">
                        <i class="fas fa-plus-circle"></i>
                        <span>Agregar Producto</span>
                    </a></li>
                    <li><a href="#inventario-actual">
                        <i class="fas fa-boxes"></i>
                        <span>Inventario Actual</span>
                    </a></li>
                    <li><a href="#historial-movimientos">
                        <i class="fas fa-history"></i>
                        <span>Historial</span>
                    </a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="contenido-principal">
                <section class="banner-admin">
                    <h1>Gestión de Stock</h1>
                </section>

                <!-- Mensajes -->
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje-exito"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="mensaje-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Estadísticas de Stock -->
                <section id="estadisticas-stock" class="seccion-admin">
                    <h2>Estadísticas de Stock</h2>
                    <div class="stats-container">
                        <?php while($stat = mysqli_fetch_assoc($stats_tipos)): 
                            $icono = '';
                            $color = '';
                            switch($stat['tipo']) { // Cambiado de 'tipo de producto' a 'tipo'
                                case 'bebida': $icono = '🥤'; $color = '#3498db'; break;
                                case 'alimento': $icono = '🍎'; $color = '#e74c3c'; break;
                                case 'limpieza': $icono = '🧹'; $color = '#9b59b6'; break;
                                case 'utensilio': $icono = '🔪'; $color = '#f39c12'; break;
                            }
                        ?>
                            <div class="stat-card" style="border-left: 4px solid <?php echo $color; ?>;">
                                <h3><?php echo $icono; ?> <?php echo ucfirst($stat['tipo']); ?></h3>
                                <p><strong>Productos:</strong> <?php echo $stat['total']; ?></p>
                                <p><strong>Stock total:</strong> <?php echo $stat['cantidad_total']; ?> unidades</p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>

                <!-- Agregar Nuevo Producto -->
                <section id="agregar-producto" class="seccion-admin">
                    <h2>Agregar Nuevo Producto</h2>
                    <div class="formulario-container">
                        <div class="formulario-seccion">
                            <form method="POST" class="formulario-admin">
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Nombre del Producto:</label>
                                        <input type="text" name="nombre" required placeholder="Ej: Coca-Cola 2L">
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Cantidad Inicial:</label>
                                        <input type="number" name="cantidad" required min="0" placeholder="Ej: 50">
                                    </div>
                                </div>
                                
                                <div class="fila-formulario">
                                    <div class="grupo-formulario">
                                        <label>Precio Unitario:</label>
                                        <input type="number" step="0.01" name="precio" required placeholder="Ej: 5.50">
                                    </div>
                                    <div class="grupo-formulario">
                                        <label>Tipo de Producto:</label>
                                        <select name="tipo" required>
                                            <option value="bebida">🥤 Bebida</option>
                                            <option value="alimento">🍎 Alimento</option>
                                            <option value="limpieza">🧹 Limpieza</option>
                                            <option value="utensilio">🔪 Utensilio</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grupo-formulario">
                                    <label>Unidad de Medida:</label>
                                    <input type="text" name="unidad_medida" placeholder="Ej: litros, kg, unidades" required>
                                </div>
                                
                                <button type="submit" name="agregar_producto" class="btn-admin">Agregar Producto</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Inventario Actual -->
                <section id="inventario-actual" class="seccion-admin">
                    <h2>Inventario Actual</h2>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Tipo</th>
                                    <th>Unidad</th>
                                    <th>Última Actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($producto = mysqli_fetch_assoc($productos)): 
                                    $clase_tipo = 'tipo-' . $producto['tipo']; // Cambiado de 'tipo de producto' a 'tipo'
                                    $clase_stock = '';
                                    if ($producto['cantidad'] < 10) $clase_stock = 'critico-stock';
                                    elseif ($producto['cantidad'] < 25) $clase_stock = 'bajo-stock';
                                ?>
                                    <tr class="<?php echo $clase_tipo . ' ' . $clase_stock; ?>">
                                        <td><?php echo $producto['producto_id']; ?></td> <!-- Cambiado de 'id producto' a 'producto_id' -->
                                        <td><strong><?php echo $producto['nombre']; ?></strong></td>
                                        <td>
                                            <span style="font-weight: bold; color: <?php echo $producto['cantidad'] < 10 ? '#e74c3c' : ($producto['cantidad'] < 25 ? '#f39c12' : '#27ae60'); ?>">
                                                <?php echo $producto['cantidad']; ?>
                                            </span>
                                        </td>
                                        <td class="precio">$<?php echo number_format($producto['precio'], 2); ?></td>
                                        <td>
                                            <?php 
                                            $icono = '';
                                            switch($producto['tipo']) { // Cambiado de 'tipo de producto' a 'tipo'
                                                case 'bebida': $icono = '🥤'; break;
                                                case 'alimento': $icono = '🍎'; break;
                                                case 'limpieza': $icono = '🧹'; break;
                                                case 'utensilio': $icono = '🔪'; break;
                                            }
                                            echo $icono . ' ' . ucfirst($producto['tipo']); 
                                            ?>
                                        </td>
                                        <td><?php echo $producto['unidad_medida']; ?></td> <!-- Cambiado de 'unidad de medida' a 'unidad_medida' -->
                                        <td><?php echo $producto['ultima_actu']; ?></td> <!-- Cambiado de 'ultima actualizacion' a 'ultima_actu' -->
                                        <td>
                                            <form method="POST" class="form-acciones">
                                                <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>"> <!-- Cambiado de 'id producto' a 'producto_id' -->
                                                <input type="number" name="cantidad_movimiento" placeholder="Cantidad" required min="0">
                                                <select name="tipo_movimiento" required>
                                                    <option value="entrada">📥 Entrada</option>
                                                    <option value="salida">📤 Salida</option>
                                                    <option value="ajuste">⚙️ Ajuste</option>
                                                </select>
                                                <input type="text" name="descripcion" placeholder="Motivo" required>
                                                <button type="submit" name="actualizar_stock" class="btn-editar">Actualizar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Historial de Movimientos -->
                <section id="historial-movimientos" class="seccion-admin">
                    <h2>Historial de Movimientos</h2>
                    <?php
                    // Consulta de historial actualizada
                    $historial = mysqli_query($conexion, "
                        SELECT h.*, p.nombre as producto_nombre 
                        FROM historial h 
                        JOIN producto p ON h.`stock_id stock` = p.stock_stock_id
                        ORDER BY h.fecha DESC 
                        LIMIT 20
                    ");
                    ?>
                    <div class="tabla-container">
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($movimiento = mysqli_fetch_assoc($historial)): 
                                    $color_tipo = '';
                                    switch($movimiento['tipo_movimiento']) { // Cambiado de 'tipo de movimiento' a 'tipo_movimiento'
                                        case 'entrada': $color_tipo = '#27ae60'; break;
                                        case 'salida': $color_tipo = '#e74c3c'; break;
                                        case 'ajuste': $color_tipo = '#f39c12'; break;
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $movimiento['fecha']; ?></td>
                                        <td><?php echo $movimiento['producto_nombre']; ?></td>
                                        <td><?php echo $movimiento['descripcion']; ?></td>
                                        <td style="color: <?php echo $color_tipo; ?>;">
                                            <?php echo ucfirst($movimiento['tipo_movimiento']); ?>
                                        </td>
                                        <td><?php echo $movimiento['cantidad']; ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <script>
        // Script para navegación suave entre secciones
        document.addEventListener('DOMContentLoaded', function() {
            // Navegación del sidebar
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            const sections = document.querySelectorAll('.seccion-admin');
            
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
            
            // Mostrar solo la primera sección al cargar
            if (sections.length > 0) {
                sections.forEach((section, index) => {
                    section.style.display = index === 0 ? 'block' : 'none';
                });
            }

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