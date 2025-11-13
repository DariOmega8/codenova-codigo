<?php
// Inicia la sesión para acceder a las variables de sesión del usuario
session_start();
// Incluye el archivo de conexión a la base de datos
include "conexion.php";

// Configurar zona horaria para Perú/Lima
date_default_timezone_set('America/Lima');

// Verifica que el usuario sea empleado o administrador, si no, redirige al inicio
if (!isset($_SESSION['es_empleado']) && !isset($_SESSION['es_administrador'])) {
    header("Location: inicio.php");
    exit();
}

// FUNCIÓN PARA LIBERAR MESAS AUTOMÁTICAMENTE DESPUÉS DE 6 HORAS
function liberarMesasAntiguas($conexion) {
    // Consulta SQL para liberar mesas ocupadas por más de 6 horas
    $sql_liberar = "UPDATE mesa 
                   SET estado = 'disponible' 
                   WHERE estado = 'ocupada' 
                   AND fecha_asig < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    mysqli_query($conexion, $sql_liberar);
}

// Ejecutar la limpieza automática al cargar la página
liberarMesasAntiguas($conexion);

// Variables iniciales
$cliente = null;
$reservas = [];
$mesas_disponibles = [];

// Procesar búsqueda de cliente por email
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar_cliente'])) {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    
    // CONSULTA ACTUALIZADA - Nuevo esquema de base de datos
    $sql_cliente = "SELECT u.id_usuario, u.nombre, u.gmail, c.cliente_id
                    FROM usuario u 
                    JOIN cliente c ON u.id_usuario = c.usuario_id_usuario
                    WHERE u.gmail = '$email'";
    
    $resultado = mysqli_query($conexion, $sql_cliente);
    
    // Si se encuentra el cliente
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $cliente = mysqli_fetch_assoc($resultado);
        $cliente_id = $cliente['cliente_id'];
        
        // Buscar reservas pendientes para hoy
        $hoy = date('Y-m-d');
        // CONSULTA ACTUALIZADA - Nuevo esquema
        $sql_reservas = "SELECT * FROM reserva 
                        WHERE cliente_cliente_id = $cliente_id 
                        AND fecha = '$hoy' 
                        AND estado = 'pendiente'";
        
        $reservas_result = mysqli_query($conexion, $sql_reservas);
        if ($reservas_result) {
            $reservas = mysqli_fetch_all($reservas_result, MYSQLI_ASSOC);
        }
        
        // Si hay reservas, buscar mesas disponibles
        if (count($reservas) > 0) {
            $cantidad_personas = $reservas[0]['cantidad'];
            // CONSULTA ACTUALIZADA - Nuevo esquema
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
        // Cliente no encontrado
        $error = "No se encontró ningún cliente con el email: $email";
    }
}

// Procesar asignación de mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_mesa'])) {
    $reserva_id = $_POST['reserva_id'];
    $mesa_id = $_POST['mesa_id'];
    $cliente_id = $_POST['cliente_id'];
    
    // Iniciar transacción para asegurar la consistencia de los datos
    mysqli_begin_transaction($conexion);
    
    try {
        // 1. Actualizar el estado de la reserva a 'confirmada' - NUEVO ESQUEMA
        $sql_actualizar_reserva = "UPDATE reserva SET estado = 'confirmada' WHERE id_reserva = $reserva_id";
        if (!mysqli_query($conexion, $sql_actualizar_reserva)) {
            throw new Exception("Error al actualizar reserva: " . mysqli_error($conexion));
        }
        
        // 2. Actualizar el estado de la mesa a 'ocupada' - NUEVO ESQUEMA
        $sql_actualizar_mesa = "UPDATE mesa 
                               SET estado = 'ocupada', 
                                   fecha_asig = NOW()
                               WHERE mesa_id = $mesa_id";
        if (!mysqli_query($conexion, $sql_actualizar_mesa)) {
            throw new Exception("Error al actualizar mesa: " . mysqli_error($conexion));
        }
        
        // 3. ELIMINAR CUALQUIER RELACIÓN EXISTENTE PARA ESTA MESA - NUEVO ESQUEMA
        $sql_eliminar_relacion = "DELETE FROM mesa_cliente WHERE mesa_mesa_id = $mesa_id";
        if (!mysqli_query($conexion, $sql_eliminar_relacion)) {
            throw new Exception("Error al eliminar relación anterior: " . mysqli_error($conexion));
        }
        
        // 4. Insertar la NUEVA relación en mesa_cliente - NUEVO ESQUEMA
        $sql_relacion_mesa = "INSERT INTO mesa_cliente (mesa_mesa_id, cliente_cliente_id)
                             VALUES ($mesa_id, $cliente_id)";
        if (!mysqli_query($conexion, $sql_relacion_mesa)) {
            throw new Exception("Error al crear nueva relación mesa-cliente: " . mysqli_error($conexion));
        }
        
        // 5. Registrar la visita en el registro - NUEVO ESQUEMA
        $sql_visita = "INSERT INTO `registro de visita` (fecha_hora, cantidad) 
                      VALUES (NOW(), 1)";
        if (!mysqli_query($conexion, $sql_visita)) {
            throw new Exception("Error al registrar visita: " . mysqli_error($conexion));
        }
        
        // Confirmar todas las operaciones si todo salió bien
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
    <!-- Header específico para empleados/administradores -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
      </div>
      <!-- Navegación principal -->
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <!-- Muestra panel de administración solo para administradores -->
           <?php if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador']): ?>
            <li><a href="administracion.php" class="btn-admin-header">Administración</a></li>
          <?php endif; ?>
          <!-- Muestra zona staff para empleados y administradores -->
          <?php if ((isset($_SESSION['es_empleado']) && $_SESSION['es_empleado']) || (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'])): ?>
            <li><a href="zona_staff.php" class="btn-staff-header">Zona Mozos</a></li>
          <?php endif; ?>
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar sesión</a></li>
        </ul>
      </nav>
    </header>

    <!-- Contenido Principal -->
    <main class="contenido-principal">
      <!-- Banner de la página -->
      <section class="banner-admin">
        <h1>Verificar Reserva y Asignar Mesa</h1>
      </section>

      <section class="seccion-admin">
        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($mensaje)): ?>
          <div class="mensaje-exito"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
          <div class="mensaje-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulario para buscar cliente por email -->
        <form class="formulario-admin" method="POST">
          <h2>Buscar Cliente</h2>
          <div class="grupo-formulario">
            <label>Email del cliente:</label>
            <input type="email" name="email" required placeholder="Ingresa el email del cliente que hizo la reserva">
          </div>
          <button type="submit" name="buscar_cliente" class="btn-admin">Buscar Cliente</button>
        </form>

        <!-- Si se encontró un cliente, mostrar su información -->
        <?php if ($cliente): ?>
          <div class="informacion" style="margin-top: 30px;">
            <h2>👤 Cliente: <?php echo $cliente['nombre']; ?></h2>
            <p><strong>Email:</strong> <?php echo $cliente['gmail']; ?></p>
            <p><strong>ID Cliente:</strong> <?php echo $cliente['cliente_id']; ?></p>
          </div>
          
          <!-- Mostrar reservas pendientes del cliente -->
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
                        <td><strong>#<?php echo $reserva['id_reserva']; ?></strong></td>
                        <td><?php echo $reserva['fecha']; ?></td>
                        <td><?php echo $reserva['hora_inicio']; ?></td>
                        <td>
                          <span class="etiqueta etiqueta-pendiente">
                            👥 <?php echo $reserva['cantidad']; ?> personas
                          </span>
                        </td>
                        <td>
                          <!-- Formulario para asignar mesa a la reserva -->
                          <?php if (count($mesas_disponibles) > 0): ?>
                            <form method="POST" class="form-acciones">
                              <input type="hidden" name="reserva_id" value="<?php echo $reserva['id_reserva']; ?>">
                              <input type="hidden" name="cliente_id" value="<?php echo $cliente['cliente_id']; ?>">
                              <select name="mesa_id" required>
                                <option value="">Seleccionar mesa...</option>
                                <?php foreach ($mesas_disponibles as $mesa): ?>
                                  <option value="<?php echo $mesa['mesa_id']; ?>">
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

          <!-- Mostrar mesas disponibles -->
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

        <!-- Mensaje si no se encontró el cliente -->
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