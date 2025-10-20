<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if (!$_SESSION['es_empleado'] && !$_SESSION['es_administrador']) {
    header("Location: inicio.php?error=No tienes permisos para acceder a esta zona");
    exit();
}

// FUNCIÓN PARA LIBERAR MESAS AUTOMÁTICAMENTE DESPUÉS DE 6 HORAS
function liberarMesasAntiguas($conexion) {
    $sql_liberar = "UPDATE mesa 
                   SET estado = 'disponible' 
                   WHERE estado = 'ocupada' 
                   AND `fecha de asignacion` < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    mysqli_query($conexion, $sql_liberar);
}

// Ejecutar la limpieza automática
liberarMesasAntiguas($conexion);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mesa'])) {
    $mesa_id = $_POST['mesa']; // Este es el id de la mesa, no el número
    $plato_principal = $_POST['pedido'] ?? '';
    $bebida = $_POST['bebida'] ?? '';
    $postre = $_POST['postre'] ?? '';
    $extra = $_POST['extra'] ?? '';
    $exclusiones = $_POST['exclusiones'] ?? '';
    
    // CONSULTA CORREGIDA - Buscar por id de mesa
    $sql_mesa = "SELECT m.`id mesa`, chm.`cliente_id cliente`, chm.`cliente_usuario_id usuario`, 
                        u.nombre as cliente_nombre
                 FROM mesa m 
                 LEFT JOIN cliente_has_mesa chm ON m.`id mesa` = chm.`mesa_id mesa`
                 LEFT JOIN cliente c ON chm.`cliente_id cliente` = c.`id cliente` 
                 LEFT JOIN usuario u ON c.`usuario_id usuario` = u.`id usuario`
                 WHERE m.`id mesa` = $mesa_id AND m.estado = 'ocupada'";
    
    $resultado_mesa = mysqli_query($conexion, $sql_mesa);
    
    if ($resultado_mesa && mysqli_num_rows($resultado_mesa) > 0) {
        $mesa_data = mysqli_fetch_assoc($resultado_mesa);
        $mesa_id = $mesa_data['id mesa'];
        $cliente_id = $mesa_data['cliente_id cliente'];
        $cliente_usuario_id = $mesa_data['cliente_usuario_id usuario'];
        
        // Crear pedido
        $sql_pedido = "INSERT INTO pedido (estado, fecha) VALUES ('pendiente', NOW())";
        if (mysqli_query($conexion, $sql_pedido)) {
            $pedido_id = mysqli_insert_id($conexion);
            
            // Insertar en mesa_has_pedido - CORREGIDO
            $sql_relacion = "INSERT INTO mesa_has_pedido (`mesa_id mesa`, `mesa_cliente_id cliente`, 
                            `mesa_cliente_usuario_id usuario`, `pedido_id pedido`) 
                            VALUES ($mesa_id, $cliente_id, $cliente_usuario_id, $pedido_id)";
            
            if (mysqli_query($conexion, $sql_relacion)) {
                // Buscar y agregar platos al pedido
                $platos_a_buscar = [];
                if (!empty($plato_principal)) $platos_a_buscar[] = $plato_principal;
                if (!empty($bebida)) $platos_a_buscar[] = $bebida;
                if (!empty($postre)) $platos_a_buscar[] = $postre;
                if (!empty($extra)) $platos_a_buscar[] = $extra;
                
                $platos_agregados = 0;
                foreach ($platos_a_buscar as $nombre_plato) {
                    $sql_plato = "SELECT `id platos` FROM platos WHERE nombre LIKE '%$nombre_plato%'";
                    $resultado_plato = mysqli_query($conexion, $sql_plato);
                    
                    if ($resultado_plato && mysqli_num_rows($resultado_plato) > 0) {
                        $plato_data = mysqli_fetch_assoc($resultado_plato);
                        $plato_id = $plato_data['id platos'];
                        
                        $sql_agregar_plato = "INSERT INTO pedido_has_platos (`pedido_id pedido`, `platos_id platos`) 
                                             VALUES ($pedido_id, $plato_id)";
                        if (mysqli_query($conexion, $sql_agregar_plato)) {
                            $platos_agregados++;
                        }
                    }
                }
                
                $mensaje = "Pedido #$pedido_id creado correctamente para Mesa $mesa_id ($platos_agregados platos agregados)";
            } else {
                $error = "Error al relacionar pedido con mesa: " . mysqli_error($conexion);
            }
        } else {
            $error = "Error al crear pedido: " . mysqli_error($conexion);
        }
    } else {
        $error = "Mesa con ID $mesa_id no encontrada o no está ocupada.";
    }
}

// CONSULTA MEJORADA para obtener mesas ocupadas
$mesas_ocupadas = mysqli_query($conexion, "
    SELECT m.*, 
           u.nombre as cliente_nombre,
           TIMEDIFF(NOW(), m.`fecha de asignacion`) as tiempo_ocupada
    FROM mesa m 
    LEFT JOIN cliente_has_mesa chm ON m.`id mesa` = chm.`mesa_id mesa`
    LEFT JOIN cliente c ON chm.`cliente_id cliente` = c.`id cliente`
    LEFT JOIN usuario u ON c.`usuario_id usuario` = u.`id usuario`
    WHERE m.estado = 'ocupada'
    AND m.`fecha de asignacion` >= DATE_SUB(NOW(), INTERVAL 6 HOUR)
    ORDER BY m.numero
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mozos - La Chacra Gourmet</title>
    <link rel="stylesheet" href="estilos/estilo_general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="contenedor-principal">
    <!-- Header -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
      </div>
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <li><a href="zona_staff.php">Mozos orden</a></li>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
        </ul>
      </nav>
      <div class="botones-sesion">
        <?php if (isset($_SESSION['id_usuario'])): ?>
          <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
          <a href="cerrar_sesion.php" class="btn-logout" role="button">Cerrar sesión</a>
        <?php else: ?>
          <a href="iniciar_sesion.html" class="btn-login" role="button">Iniciar sesión</a>
          <a href="registrarse_cliente.html" class="btn-register" role="button">Registrarse</a>
        <?php endif; ?>
      </div>
    </header>

    <!-- Contenido Principal -->
    <main class="contenido-principal">
      <section class="banner-admin">
        <h1>Zona de Mozos</h1>
      </section>

      <div class="verificar-reserva-container">
        <a href="verificar_reserva.php" class="btn-admin">🔍 Verificar Reserva</a>
      </div>
      
      <?php if (isset($mensaje)): ?>
        <div class="mensaje-exito"><?php echo $mensaje; ?></div>
      <?php endif; ?>
      
      <?php if (isset($error)): ?>
        <div class="mensaje-error"><?php echo $error; ?></div>
      <?php endif; ?>

      <section class="seccion-admin">
               <h2>Tomar Pedido</h2>
                 <form class="formulario-admin" method="POST">
                   <div class="grupo-formulario">
                       <label for="mesa">Mesa</label>
                          <select id="mesa" name="mesa" required>
                             <option value="">Seleccionar mesa...</option>
                         <?php
                               $sql_mesas_all = "SELECT * FROM mesa ORDER BY numero";
                                $mesas_all_result = mysqli_query($conexion, $sql_mesas_all);
                              if ($mesas_all_result && mysqli_num_rows($mesas_all_result) > 0) {
                              while ($m = mysqli_fetch_assoc($mesas_all_result)) {
                             $display = 'Mesa ' . htmlspecialchars($m['numero']) . ' - ' . htmlspecialchars($m['estado']);
                             echo '<option value="' . intval($m['id mesa']) . '">' . $display . '</option>';
                                }
                           } else {
                                 echo '<option value="">No hay mesas registradas</option>';
                             }
                             ?>
                    </select>
              </div>

          <div class="fila-formulario">
            <div class="grupo-formulario">
              <label for="pedido">Plato principal</label>
              <input type="text" id="pedido" name="pedido" placeholder="Ej: Lomo Saltado">
            </div>
            
            <div class="grupo-formulario">
              <label for="bebida">Bebidas</label>
              <input type="text" id="bebida" name="bebida" placeholder="Ej: Coca-Cola">
            </div>
          </div>

          <div class="fila-formulario">
            <div class="grupo-formulario">
              <label for="postre">Postre</label>
              <input type="text" id="postre" name="postre" placeholder="Ej: Flan">
            </div>

            <div class="grupo-formulario">
              <label for="extra">Extras</label>
              <input type="text" id="extra" name="extra" placeholder="Ej: Papas fritas">
            </div>
          </div>

          <div class="grupo-formulario">
            <label for="exclusiones">Exclusiones (Alergias/Preferencias)</label>
            <input type="text" id="exclusiones" name="exclusiones" placeholder="Ej: Sin gluten">
          </div>

          <button type="submit" class="btn-admin">Confirmar Pedido</button>
        </form>
      </section>

      <section class="seccion-admin">
        <h2>Pedidos Activos</h2>
        <?php
        $pedidos_activos = mysqli_query($conexion, "
          SELECT p.`id pedido`, m.numero as mesa_numero, p.estado
          FROM pedido p
          JOIN mesa_has_pedido mp ON p.`id pedido` = mp.`pedido_id pedido`
          JOIN mesa m ON mp.`mesa_id mesa` = m.`id mesa`
          WHERE p.estado != 'entregado'
        ");
        
        if ($pedidos_activos && mysqli_num_rows($pedidos_activos) > 0): 
        ?>
          <div class="tabla-container">
            <table class="tabla-admin">
              <thead>
                <tr>
                  <th>Pedido ID</th>
                  <th>Mesa</th>
                  <th>Estado</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php while($pedido = mysqli_fetch_assoc($pedidos_activos)): ?>
                  <tr>
                    <td>#<?php echo $pedido['id pedido']; ?></td>
                    <td>Mesa <?php echo $pedido['mesa_numero']; ?></td>
                    <td>
                      <span class="etiqueta etiqueta-<?php echo $pedido['estado']; ?>">
                        <?php echo ucfirst($pedido['estado']); ?>
                      </span>
                    </td>
                    <td>
                      <form method='POST' action='actualizar_pedido.php' class="form-acciones">
                        <input type='hidden' name='pedido_id' value='<?php echo $pedido['id pedido']; ?>'>
                        <select name='nuevo_estado' onchange='this.form.submit()'>
                          <option value='pendiente' <?php echo ($pedido['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                          <option value='preparacion' <?php echo ($pedido['estado'] == 'preparacion') ? 'selected' : ''; ?>>En preparación</option>
                          <option value='listo' <?php echo ($pedido['estado'] == 'listo') ? 'selected' : ''; ?>>Listo para servir</option>
                          <option value='entregado' <?php echo ($pedido['estado'] == 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                        </select>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p style="text-align: center; color: #7f8c8d;">No hay pedidos activos</p>
        <?php endif; ?>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - ZONA DE MOZOS</div>
      <div class="footer-buttons">
        <a href="inicio.php" class="btn-enlace">Volver al Inicio</a>
        <a href="verificar_reserva.php" class="btn-enlace">Verificar Reservas</a>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>