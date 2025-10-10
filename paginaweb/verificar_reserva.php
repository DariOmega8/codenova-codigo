<?php
session_start();
include "conexion.php";

// Configurar zona horaria
date_default_timezone_set('America/Lima');

if (!isset($_SESSION['es_empleado']) && !isset($_SESSION['es_administrador'])) {
    header("Location: inicio.php");
    exit();
}

// FUNCIÓN PARA LIBERAR MESAS AUTOMÁTICAMENTE DESPUÉS DE 6 HORAS (SOLO LIMPIEZA)
function liberarMesasAntiguas($conexion) {
    $sql_liberar = "UPDATE mesa 
                   SET estado = 'disponible' 
                   WHERE estado = 'ocupada' 
                   AND `fecha de asignacion` < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    mysqli_query($conexion, $sql_liberar);
}

// Ejecutar la limpieza automática
liberarMesasAntiguas($conexion);

$cliente = null;
$reservas = [];
$mesas_disponibles = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar_cliente'])) {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    
    $sql_cliente = "SELECT u.`id usuario`, u.nombre, u.gmail, c.`id cliente`
                    FROM usuario u 
                    JOIN cliente c ON u.`id usuario` = c.`usuario_id usuario`
                    WHERE u.gmail = '$email'";
    
    $resultado = mysqli_query($conexion, $sql_cliente);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $cliente = mysqli_fetch_assoc($resultado);
        $cliente_id = $cliente['id cliente'];
        
        $hoy = date('Y-m-d');
        $sql_reservas = "SELECT * FROM reserva 
                        WHERE `cliente_id cliente` = $cliente_id 
                        AND fecha = '$hoy' 
                        AND estado = 'Pendiente'";
        
        $reservas_result = mysqli_query($conexion, $sql_reservas);
        if ($reservas_result) {
            $reservas = mysqli_fetch_all($reservas_result, MYSQLI_ASSOC);
        }
        
        if (count($reservas) > 0) {
            $cantidad_personas = $reservas[0]['cantidad'];
            $sql_mesas = "SELECT * FROM mesa 
                         WHERE estado = 'disponible' 
                         AND capacidad >= $cantidad_personas
                         ORDER BY capacidad ASC";
            $mesas_result = mysqli_query($conexion, $sql_mesas);
            if ($mesas_result) {
                $mesas_disponibles = mysqli_fetch_all($mesas_result, MYSQLI_ASSOC);
            }
        }
    } else {
        $error = "No se encontró ningún cliente con el email: $email";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_mesa'])) {
    $reserva_id = $_POST['reserva_id'];
    $mesa_id = $_POST['mesa_id'];
    $cliente_id = $_POST['cliente_id'];
    
    // Iniciar transacción
    mysqli_begin_transaction($conexion);
    
    try {
        // 1. Actualizar el estado de la reserva
        $sql_actualizar_reserva = "UPDATE reserva SET estado = 'confirmada' WHERE `id reserva` = $reserva_id";
        if (!mysqli_query($conexion, $sql_actualizar_reserva)) {
            throw new Exception("Error al actualizar reserva: " . mysqli_error($conexion));
        }
        
        // 2. Actualizar el estado de la mesa a 'ocupada'
        $sql_actualizar_mesa = "UPDATE mesa 
                               SET estado = 'ocupada', 
                                   `fecha de asignacion` = NOW()
                               WHERE `id mesa` = $mesa_id";
        if (!mysqli_query($conexion, $sql_actualizar_mesa)) {
            throw new Exception("Error al actualizar mesa: " . mysqli_error($conexion));
        }
        
        // 3. OBTENER EL usuario_id DEL CLIENTE
        $sql_usuario_cliente = "SELECT `usuario_id usuario` FROM cliente WHERE `id cliente` = $cliente_id";
        $result_usuario = mysqli_query($conexion, $sql_usuario_cliente);
        if (!$result_usuario) {
            throw new Exception("Error en la consulta para obtener usuario_id: " . mysqli_error($conexion));
        }
        if (mysqli_num_rows($result_usuario) == 0) {
            throw new Exception("No se encontró el cliente con ID: $cliente_id");
        }
        $cliente_data = mysqli_fetch_assoc($result_usuario);
        $cliente_usuario_id = $cliente_data['usuario_id usuario']; // Asegúrate de que el nombre de la columna es correcto
        
        // 4. ELIMINAR CUALQUIER RELACIÓN EXISTENTE PARA ESTA MESA
        $sql_eliminar_relacion = "DELETE FROM cliente_has_mesa WHERE `mesa_id mesa` = $mesa_id";
        if (!mysqli_query($conexion, $sql_eliminar_relacion)) {
            throw new Exception("Error al eliminar relación anterior: " . mysqli_error($conexion));
        }
        
        // 5. Insertar la NUEVA relación en cliente_has_mesa
        $sql_relacion_mesa = "INSERT INTO cliente_has_mesa (`cliente_id cliente`, `cliente_usuario_id usuario`, `mesa_id mesa`)
                             VALUES ($cliente_id, $cliente_usuario_id, $mesa_id)";
        if (!mysqli_query($conexion, $sql_relacion_mesa)) {
            throw new Exception("Error al crear nueva relación mesa-cliente: " . mysqli_error($conexion));
        }
        
        // 6. Registrar la visita
        $sql_visita = "INSERT INTO `registro de visita` (`fecha hora`, `cantidad`) 
                      VALUES (NOW(), 1)";
        if (!mysqli_query($conexion, $sql_visita)) {
            throw new Exception("Error al registrar visita: " . mysqli_error($conexion));
        }
        
        // Confirmar todas las operaciones
        mysqli_commit($conexion);
        $mensaje = "Mesa asignada correctamente al cliente y reserva confirmada";
        
    } catch (Exception $e) {
        // Revertir todas las operaciones en caso de error
        mysqli_rollback($conexion);
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verificar Reserva - La Chacra Gourmet</title>
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
          <li><a href="zona_staff.php">Volver a Mozos</a></li>
          <?php if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador']): ?>
            <li><a href="administracion.php">Panel Admin</a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <div class="botones-sesion">
        <a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión</a>
      </div>
    </header>

    <!-- Contenido Principal -->
    <main class="contenido-principal">
      <section class="banner-admin">
        <h1>Verificar Reserva y Asignar Mesa</h1>
      </section>

      <section class="seccion-admin">
        <?php if (isset($mensaje)): ?>
          <div class="mensaje-exito"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
          <div class="mensaje-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="formulario-admin" method="POST">
          <h2>Buscar Cliente</h2>
          <div class="grupo-formulario">
            <label>Email del cliente:</label>
            <input type="email" name="email" required placeholder="Ingresa el email del cliente que hizo la reserva">
          </div>
          <button type="submit" name="buscar_cliente" class="btn-admin">Buscar Cliente</button>
        </form>

        <?php if ($cliente): ?>
          <div class="informacion" style="margin-top: 30px;">
            <h2>👤 Cliente: <?php echo $cliente['nombre']; ?></h2>
            <p><strong>Email:</strong> <?php echo $cliente['gmail']; ?></p>
            <p><strong>ID Cliente:</strong> <?php echo $cliente['id cliente']; ?></p>
          </div>
          
          <?php if (count($reservas) > 0): ?>
            <div class="seccion-admin" style="margin-top: 30px;">
              <h3>Reservas Pendientes para Hoy:</h3>
              <div class="tabla-container">
                <table class="tabla-admin">
                  <thead>
                    <tr>
                      <th>ID Reserva</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Personas</th>
                      <th>Asignar Mesa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                      <tr>
                        <td><strong>#<?php echo $reserva['id reserva']; ?></strong></td>
                        <td><?php echo $reserva['fecha']; ?></td>
                        <td><?php echo $reserva['hora de inicio']; ?></td>
                        <td>
                          <span class="etiqueta etiqueta-pendiente">
                            👥 <?php echo $reserva['cantidad']; ?> personas
                          </span>
                        </td>
                        <td>
                          <?php if (count($mesas_disponibles) > 0): ?>
                            <form method="POST" class="form-acciones">
                              <input type="hidden" name="reserva_id" value="<?php echo $reserva['id reserva']; ?>">
                              <input type="hidden" name="cliente_id" value="<?php echo $cliente['id cliente']; ?>">
                              <select name="mesa_id" required>
                                <option value="">Seleccionar mesa...</option>
                                <?php foreach ($mesas_disponibles as $mesa): ?>
                                  <option value="<?php echo $mesa['id mesa']; ?>">
                                    🪑 Mesa <?php echo $mesa['numero']; ?> 
                                    (Capacidad: <?php echo $mesa['capacidad']; ?> personas)
                                  </option>
                                <?php endforeach; ?>
                              </select>
                              <button type="submit" name="asignar_mesa" class="btn-admin">✅ Asignar Mesa</button>
                            </form>
                          <?php else: ?>
                            <span style="color: #e74c3c;">❌ No hay mesas disponibles</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php else: ?>
            <div class="informacion">
              <p>ℹ No hay reservas pendientes para hoy.</p>
            </div>
          <?php endif; ?>

          <?php if (count($mesas_disponibles) > 0): ?>
            <div class="seccion-admin" style="margin-top: 30px;">
              <h3>Mesas Disponibles:</h3>
              <div class="tabla-container">
                <table class="tabla-admin">
                  <thead>
                    <tr>
                      <th>Número</th>
                      <th>Capacidad</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($mesas_disponibles as $mesa): ?>
                      <tr>
                        <td>Mesa <?php echo $mesa['numero']; ?></td>
                        <td><?php echo $mesa['capacidad']; ?> personas</td>
                        <td><span class="estado-disponible">Disponible</span></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endif; ?>

        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !$cliente): ?>
          <div class="mensaje-error">
            <p>No se encontró ningún cliente con ese email. Verifica que el cliente esté registrado en el sistema.</p>
          </div>
        <?php endif; ?>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-texto">LA CHACRA GOURMET - VERIFICAR RESERVA</div>
      <div class="footer-buttons">
        <a href="zona_staff.php" class="btn-enlace">Volver a Mozos</a>
        <a href="inicio.php" class="btn-enlace">Ir al Inicio</a>
      </div>
    </footer>
  </div>
</body>
</html>