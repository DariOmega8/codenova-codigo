<?php
// Inicia la sesión para acceder a las variables de sesión del usuario
session_start();
// Incluye el archivo de conexión a la base de datos
include "conexion.php";

// Verifica si el usuario está autenticado, si no, redirige al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciar_sesion.html");
    exit();
}

// Verifica que el usuario sea empleado o administrador
if (!$_SESSION['es_empleado'] && !$_SESSION['es_administrador']) {
    header("Location: inicio.php?error=No tienes permisos para acceder a esta zona");
    exit();
}

// Cargar todos los platos para los selects del formulario
$platos_query = mysqli_query($conexion, "SELECT plato_id, nombre, precio FROM plato ORDER BY nombre");
$platos = [];
while($plato = mysqli_fetch_assoc($platos_query)) {
    $platos[] = $plato;
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mesa'])) {
    $mesa_id = $_POST['mesa'];
    $exclusiones = $_POST['exclusiones'] ?? '';
    
    // Nuevo esquema de base de datos
    $sql_mesa = "SELECT m.mesa_id, mc.cliente_cliente_id, 
                        u.nombre as cliente_nombre
                 FROM mesa m 
                 LEFT JOIN mesa_cliente mc ON m.mesa_id = mc.mesa_mesa_id
                 LEFT JOIN cliente c ON mc.cliente_cliente_id = c.cliente_id 
                 LEFT JOIN usuario u ON c.usuario_id_usuario = u.id_usuario
                 WHERE m.mesa_id = $mesa_id AND m.estado = 'ocupada'";
    
    $resultado_mesa = mysqli_query($conexion, $sql_mesa);
    
    // Si se encuentra la mesa ocupada
    if ($resultado_mesa && mysqli_num_rows($resultado_mesa) > 0) {
        $mesa_data = mysqli_fetch_assoc($resultado_mesa);
        $mesa_id = $mesa_data['mesa_id'];
        $cliente_id = $mesa_data['cliente_cliente_id'];
        
        // Crear pedido 
        $sql_pedido = "INSERT INTO pedido (estado, fecha_hora, mesa_mesa_id) 
                      VALUES ('recibido', NOW(), $mesa_id)";
        if (mysqli_query($conexion, $sql_pedido)) {
            $pedido_id = mysqli_insert_id($conexion);
            $platos_agregados = 0;
            $total_pedido = 0;
            
            // Función para procesar platos de manera segura
            function procesarPlato($conexion, $pedido_id, $plato_id, $cantidad, &$platos_agregados, &$total_pedido) {
                if (empty($plato_id) || empty($cantidad)) {
                    return true; // Saltar si está vacío
                }
                
                $plato_id = intval($plato_id);
                $cantidad = intval($cantidad);
                
                if ($plato_id <= 0 || $cantidad <= 0) {
                    return true; // Saltar si los valores no son válidos
                }
                
                // Verificar si ya existe este plato en el pedido
                $sql_verificar = "SELECT * FROM pedido_detalle 
                                 WHERE pedido_pedido_id = $pedido_id AND plato_plato_id = $plato_id";
                $result_verificar = mysqli_query($conexion, $sql_verificar);
                
                if ($result_verificar && mysqli_num_rows($result_verificar) > 0) {
                    // Si ya existe, actualizar la cantidad
                    $existing = mysqli_fetch_assoc($result_verificar);
                    $nueva_cantidad = $existing['cantidad'] + $cantidad;
                    $precio_unitario = $existing['precio_total'] / $existing['cantidad'];
                    $nuevo_precio_total = $precio_unitario * $nueva_cantidad;
                    
                    $sql_actualizar = "UPDATE pedido_detalle 
                                      SET cantidad = $nueva_cantidad, precio_total = $nuevo_precio_total 
                                      WHERE pedido_pedido_id = $pedido_id AND plato_plato_id = $plato_id";
                    
                    if (mysqli_query($conexion, $sql_actualizar)) {
                        $total_pedido += ($nuevo_precio_total - $existing['precio_total']);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    // Si no existe, insertar nuevo
                    $sql_precio = "SELECT precio FROM plato WHERE plato_id = $plato_id";
                    $result_precio = mysqli_query($conexion, $sql_precio);
                    
                    if ($result_precio && mysqli_num_rows($result_precio) > 0) {
                        $precio_data = mysqli_fetch_assoc($result_precio);
                        $precio_total = $precio_data['precio'] * $cantidad;
                        $total_pedido += $precio_total;
                        
                        $sql_agregar_plato = "INSERT INTO pedido_detalle (pedido_pedido_id, plato_plato_id, cantidad, precio_total) 
                                             VALUES ($pedido_id, $plato_id, $cantidad, $precio_total)";
                        
                        if (mysqli_query($conexion, $sql_agregar_plato)) {
                            $platos_agregados++;
                            return true;
                        }
                    }
                }
                return false;
            }
            
            // Procesar platos principales
            if (isset($_POST['platos_principales']) && is_array($_POST['platos_principales'])) {
                foreach ($_POST['platos_principales'] as $index => $plato_id) {
                    if (isset($_POST['cantidades_principales'][$index])) {
                        procesarPlato($conexion, $pedido_id, $plato_id, $_POST['cantidades_principales'][$index], $platos_agregados, $total_pedido);
                    }
                }
            }
            
            // Procesar bebidas
            if (isset($_POST['bebidas']) && is_array($_POST['bebidas'])) {
                foreach ($_POST['bebidas'] as $index => $plato_id) {
                    if (isset($_POST['cantidades_bebidas'][$index])) {
                        procesarPlato($conexion, $pedido_id, $plato_id, $_POST['cantidades_bebidas'][$index], $platos_agregados, $total_pedido);
                    }
                }
            }
            
            // Procesar postres
            if (isset($_POST['postres']) && is_array($_POST['postres'])) {
                foreach ($_POST['postres'] as $index => $plato_id) {
                    if (isset($_POST['cantidades_postres'][$index])) {
                        procesarPlato($conexion, $pedido_id, $plato_id, $_POST['cantidades_postres'][$index], $platos_agregados, $total_pedido);
                    }
                }
            }
            
            // Procesar extras
            if (isset($_POST['extras']) && is_array($_POST['extras'])) {
                foreach ($_POST['extras'] as $index => $plato_id) {
                    if (isset($_POST['cantidades_extras'][$index])) {
                        procesarPlato($conexion, $pedido_id, $plato_id, $_POST['cantidades_extras'][$index], $platos_agregados, $total_pedido);
                    }
                }
            }
            
            $mensaje = "Pedido #$pedido_id creado correctamente para Mesa $mesa_id<br>
                       Total de platos: $platos_agregados<br>
                       Total del pedido: $" . number_format($total_pedido, 2);
        } else {
            $error = "Error al crear pedido: " . mysqli_error($conexion);
        }
    } else {
        $error = "Mesa con ID $mesa_id no encontrada o no está ocupada.";
    }
}

// obtener mesas ocupadas
$mesas_ocupadas = mysqli_query($conexion, "
    SELECT m.*, 
           u.nombre as cliente_nombre,
           TIMEDIFF(NOW(), m.fecha_asig) as tiempo_ocupada
    FROM mesa m 
    LEFT JOIN mesa_cliente mc ON m.mesa_id = mc.mesa_mesa_id
    LEFT JOIN cliente c ON mc.cliente_cliente_id = c.cliente_id
    LEFT JOIN usuario u ON c.usuario_id_usuario = u.id_usuario
    WHERE m.estado = 'ocupada'
    AND m.fecha_asig >= DATE_SUB(NOW(), INTERVAL 6 HOUR)
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
    <!-- Header con navegación principal -->
    <header class="menu">
      <div class="logo">
        <img src="estilos/imagenes/logo.jpeg" alt="La Chacra Gourmet" class="logo-img" onerror="this.style.display='none'">
      </div>
      <nav class="navegacion-principal">
        <ul>
          <li><a href="inicio.php">Inicio</a></li>
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <!-- Muestra enlace para empleados solo si el usuario es empleado -->
          <?php if (isset($_SESSION['es_empleado']) && $_SESSION['es_empleado'] === true): ?>
            <li><a href="zona_staff.php">Mozos orden</a></li>
          <?php endif; ?>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="galeria.php">Galería</a></li>
          <!-- Muestra panel de administración solo para administradores -->
          <?php 
          if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
            echo '<li><a href="administracion.php">Panel Admin</a></li>';
          }
          ?>
          <!-- Enlaces condicionales según el estado de autenticación -->
          <?php if (isset($_SESSION['id_usuario'])): ?>
            <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>)</a></li>
          <?php else: ?>
            <li><a href="iniciar_sesion.html" class="btn-login">Iniciar sesión</a></li>
            <li><a href="registrarse_cliente.html" class="btn-register">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <!-- Contenido Principal -->
    <main class="contenido-principal">
      <!-- Banner de la página -->
      <section class="banner-admin">
        <h1>Zona de Mozos</h1>
      </section>

      <!-- Enlace para verificar reservas -->
      <div class="verificar-reserva-container">
        <a href="verificar_reserva.php" class="btn-admin">🔍 Verificar Reserva</a>
      </div>
      
      <!-- Mostrar mensajes de éxito o error -->
      <?php if (isset($mensaje)): ?>
        <div class="mensaje-exito"><?php echo $mensaje; ?></div>
      <?php endif; ?>
      
      <?php if (isset($error)): ?>
        <div class="mensaje-error"><?php echo $error; ?></div>
      <?php endif; ?>

      <!-- Sección principal del formulario de pedidos -->
      <section class="seccion-admin">
        <h2>Tomar Pedido</h2>
        <form class="formulario-admin" method="POST" id="form-pedido">
          <!-- Selección de mesa -->
          <div class="grupo-formulario">
            <label for="mesa">Mesa</label>
            <select id="mesa" name="mesa" required class="select-plato">
              <option value="">Seleccionar mesa...</option>
              <?php
                // Consulta todas las mesas para el dropdown
                $sql_mesas_all = "SELECT * FROM mesa ORDER BY numero";
                $mesas_all_result = mysqli_query($conexion, $sql_mesas_all);
                if ($mesas_all_result && mysqli_num_rows($mesas_all_result) > 0) {
                  while ($m = mysqli_fetch_assoc($mesas_all_result)) {
                    $display = 'Mesa ' . htmlspecialchars($m['numero']) . ' - ' . htmlspecialchars($m['estado']);
                    echo '<option value="' . intval($m['mesa_id']) . '">' . $display . '</option>';
                  }
                } else {
                  echo '<option value="">No hay mesas registradas</option>';
                }
              ?>
            </select>
          </div>

          <!-- Sección de Platos Principales -->
          <div class="categoria-platos">
            <h3>Platos Principales</h3>
            <div id="platos-principales-container">
              <!-- Fila inicial de plato principal -->
              <div class="fila-plato">
                <div class="grupo-formulario">
                  <label>Plato Principal</label>
                  <select name="platos_principales[]" class="select-plato" onchange="actualizarTotal()">
                    <option value="">Seleccionar plato...</option>
                    <?php foreach($platos as $plato): ?>
                      <option value="<?php echo $plato['plato_id']; ?>" data-precio="<?php echo $plato['precio']; ?>">
                        <?php echo htmlspecialchars($plato['nombre']); ?> - <span class="plato-precio">$<?php echo number_format($plato['precio'], 2); ?></span>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="grupo-formulario">
                  <label>Cantidad</label>
                  <input type="number" name="cantidades_principales[]" min="1" value="1" onchange="actualizarTotal()">
                </div>
                <button type="button" class="btn-remover" onclick="removerFila(this)">✕</button>
              </div>
            </div>
            <button type="button" class="btn-agregar" onclick="agregarPlato('principales')">+ Agregar Plato Principal</button>
          </div>

          <!-- Sección de Bebidas -->
          <div class="categoria-platos">
            <h3>Bebidas</h3>
            <div id="bebidas-container">
              <!-- Fila inicial de bebida -->
              <div class="fila-plato">
                <div class="grupo-formulario">
                  <label>Bebida</label>
                  <select name="bebidas[]" class="select-plato" onchange="actualizarTotal()">
                    <option value="">Seleccionar bebida...</option>
                    <?php foreach($platos as $plato): ?>
                      <option value="<?php echo $plato['plato_id']; ?>" data-precio="<?php echo $plato['precio']; ?>">
                        <?php echo htmlspecialchars($plato['nombre']); ?> - <span class="plato-precio">$<?php echo number_format($plato['precio'], 2); ?></span>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="grupo-formulario">
                  <label>Cantidad</label>
                  <input type="number" name="cantidades_bebidas[]" min="1" value="1" onchange="actualizarTotal()">
                </div>
                <button type="button" class="btn-remover" onclick="removerFila(this)">✕</button>
              </div>
            </div>
            <button type="button" class="btn-agregar" onclick="agregarPlato('bebidas')">+ Agregar Bebida</button>
          </div>

          <!-- Sección de Postres -->
          <div class="categoria-platos">
            <h3>Postres</h3>
            <div id="postres-container">
              <!-- Fila inicial de postre -->
              <div class="fila-plato">
                <div class="grupo-formulario">
                  <label>Postre</label>
                  <select name="postres[]" class="select-plato" onchange="actualizarTotal()">
                    <option value="">Seleccionar postre...</option>
                    <?php foreach($platos as $plato): ?>
                      <option value="<?php echo $plato['plato_id']; ?>" data-precio="<?php echo $plato['precio']; ?>">
                        <?php echo htmlspecialchars($plato['nombre']); ?> - <span class="plato-precio">$<?php echo number_format($plato['precio'], 2); ?></span>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="grupo-formulario">
                  <label>Cantidad</label>
                  <input type="number" name="cantidades_postres[]" min="1" value="1" onchange="actualizarTotal()">
                </div>
                <button type="button" class="btn-remover" onclick="removerFila(this)">✕</button>
              </div>
            </div>
            <button type="button" class="btn-agregar" onclick="agregarPlato('postres')">+ Agregar Postre</button>
          </div>

          <!-- Sección de Extras -->
          <div class="categoria-platos">
            <h3>Extras</h3>
            <div id="extras-container">
              <!-- Fila inicial de extra -->
              <div class="fila-plato">
                <div class="grupo-formulario">
                  <label>Extra</label>
                  <select name="extras[]" class="select-plato" onchange="actualizarTotal()">
                    <option value="">Seleccionar extra...</option>
                    <?php foreach($platos as $plato): ?>
                      <option value="<?php echo $plato['plato_id']; ?>" data-precio="<?php echo $plato['precio']; ?>">
                        <?php echo htmlspecialchars($plato['nombre']); ?> - <span class="plato-precio">$<?php echo number_format($plato['precio'], 2); ?></span>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="grupo-formulario">
                  <label>Cantidad</label>
                  <input type="number" name="cantidades_extras[]" min="1" value="1" onchange="actualizarTotal()">
                </div>
                <button type="button" class="btn-remover" onclick="removerFila(this)">✕</button>
              </div>
            </div>
            <button type="button" class="btn-agregar" onclick="agregarPlato('extras')">+ Agregar Extra</button>
          </div>

          <!-- Resumen Total del pedido -->
          <div class="resumen-total" id="resumen-total">
            Total del Pedido: $0.00
          </div>

          <!-- Campo para exclusiones alimenticias -->
          <div class="grupo-formulario">
            <label for="exclusiones">Exclusiones (Alergias/Preferencias)</label>
            <input type="text" id="exclusiones" name="exclusiones" placeholder="Ej: Sin gluten, vegetariano, etc.">
          </div>

          <button type="submit" class="btn-admin">Confirmar Pedido</button>
        </form>
      </section>

      <!-- Sección de Pedidos Activos -->
      <section class="seccion-admin">
        <h2>Pedidos Activos</h2>
        <?php
        // Consulta para obtener pedidos activos
        $pedidos_activos = mysqli_query($conexion, "
          SELECT p.pedido_id, m.numero as mesa_numero, p.estado,
                 SUM(pd.precio_total) as total_pedido
          FROM pedido p
          JOIN mesa m ON p.mesa_mesa_id = m.mesa_id
          LEFT JOIN pedido_detalle pd ON p.pedido_id = pd.pedido_pedido_id
          WHERE p.estado != 'entregado' AND p.estado != 'cancelado'
          GROUP BY p.pedido_id
          ORDER BY p.fecha_hora DESC
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
                  <th>Total</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php while($pedido = mysqli_fetch_assoc($pedidos_activos)): ?>
                  <tr>
                    <td>#<?php echo $pedido['pedido_id']; ?></td>
                    <td>Mesa <?php echo $pedido['mesa_numero']; ?></td>
                    <td>
                      <span class="etiqueta etiqueta-<?php echo $pedido['estado']; ?>">
                        <?php echo ucfirst($pedido['estado']); ?>
                      </span>
                    </td>
                    <td class="precio">$<?php echo number_format($pedido['total_pedido'] ?? 0, 2); ?></td>
                    <td>
                      <!-- Formulario para actualizar estado del pedido -->
                      <form method='POST' action='actualizar_pedido.php' class="form-acciones">
                        <input type='hidden' name='pedido_id' value='<?php echo $pedido['pedido_id']; ?>'>
                        <select name='nuevo_estado' onchange='this.form.submit()' class="select-plato">
                          <option value='recibido' <?php echo ($pedido['estado'] == 'recibido') ? 'selected' : ''; ?>>Recibido</option>
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

 <!-- JavaScript para la gestión dinámica del formulario -->
 <script>
    // Mapeo de tipos de platos a IDs de contenedor
    const contenedoresMap = {
        'principales': 'platos-principales-container',
        'bebidas': 'bebidas-container', 
        'postres': 'postres-container',
        'extras': 'extras-container'
    };

    // Función para agregar nueva fila de plato
    function agregarPlato(tipo) {
        const containerId = contenedoresMap[tipo];
        const container = document.getElementById(containerId);
        
        if (!container) {
            console.error('Contenedor no encontrado:', containerId);
            return;
        }
        
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'fila-plato';
        nuevaFila.innerHTML = `
            <div class="grupo-formulario">
                <label>${tipo.charAt(0).toUpperCase() + tipo.slice(1)}</label>
                <select name="${tipo}[]" class="select-plato" onchange="actualizarTotal(); verificarDuplicados(this);">
                    <option value="">Seleccionar ${tipo}...</option>
                    <?php foreach($platos as $plato): ?>
                        <option value="<?php echo $plato['plato_id']; ?>" data-precio="<?php echo $plato['precio']; ?>">
                            <?php echo htmlspecialchars($plato['nombre']); ?> - <span class="plato-precio">$<?php echo number_format($plato['precio'], 2); ?></span>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grupo-formulario">
                <label>Cantidad</label>
                <input type="number" name="cantidades_${tipo}[]" min="1" value="1" onchange="actualizarTotal();">
            </div>
            <button type="button" class="btn-remover" onclick="removerFila(this)">✕</button>
        `;
        container.appendChild(nuevaFila);
    }

    // Función para remover fila
    function removerFila(boton) {
        const fila = boton.parentElement;
        const container = fila.parentElement;
        
        // No permitir eliminar la última fila de cada categoría
        const filasEnCategoria = container.querySelectorAll('.fila-plato');
        
        if (filasEnCategoria.length > 1) {
            fila.remove();
            actualizarTotal();
        } else {
            // Si es la última fila, solo resetear los valores
            const select = fila.querySelector('select');
            const input = fila.querySelector('input[type="number"]');
            if (select) select.value = '';
            if (input) input.value = '1';
            
            // Remover advertencias de duplicados si existen
            const warning = fila.querySelector('.duplicado-warning');
            if (warning) warning.remove();
            
            actualizarTotal();
        }
    }

    // Función para verificar duplicados en el cliente
    function verificarDuplicados(selectElement) {
        const categoria = selectElement.name.replace('[]', '');
        const platoId = selectElement.value;
        const fila = selectElement.closest('.fila-plato');
        
        if (!platoId) {
            // Si no hay plato seleccionado, quitar advertencia si existe
            const existingWarning = fila.querySelector('.duplicado-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
            return;
        }
        
        // Buscar otros selects en la misma categoría con el mismo plato
        const otrosSelects = document.querySelectorAll(`select[name="${categoria}[]"]:not([value=""])`);
        let duplicados = 0;
        
        otrosSelects.forEach(select => {
            if (select.value === platoId && select !== selectElement) {
                duplicados++;
            }
        });
        
        if (duplicados > 0) {
            // Mostrar advertencia
            const existingWarning = fila.querySelector('.duplicado-warning');
            if (!existingWarning) {
                const warning = document.createElement('div');
                warning.className = 'duplicado-warning';
                warning.style.color = '#F44336';
                warning.style.fontSize = '0.8rem';
                warning.style.marginTop = '5px';
                warning.innerHTML = '⚠ Este plato ya está en el pedido';
                fila.querySelector('.grupo-formulario').appendChild(warning);
            }
        } else {
            // Remover advertencia si existe
            const existingWarning = fila.querySelector('.duplicado-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
        }
    }

    // Función para calcular el total del pedido
    function actualizarTotal() {
        let total = 0;
        
        // Calcular total de todas las categorías
        const categorias = [
            {name: 'platos_principales', container: 'platos-principales-container'},
            {name: 'bebidas', container: 'bebidas-container'},
            {name: 'postres', container: 'postres-container'},
            {name: 'extras', container: 'extras-container'}
        ];
        
        categorias.forEach(categoria => {
            const selects = document.querySelectorAll(`#${categoria.container} select[name="${categoria.name}[]"]`);
            const inputs = document.querySelectorAll(`#${categoria.container} input[name="cantidades_${categoria.name.split('_')[1] || categoria.name}[]"]`);
            
            selects.forEach((select, index) => {
                if (select.value && inputs[index]) {
                    const precio = parseFloat(select.selectedOptions[0].getAttribute('data-precio')) || 0;
                    const cantidad = parseFloat(inputs[index].value) || 0;
                    total += precio * cantidad;
                }
            });
        });
        
        document.getElementById('resumen-total').textContent = 'Total del Pedido: $' + total.toFixed(2);
    }

    // Función para validar el formulario antes de enviar
    function validarFormulario() {
        let formularioValido = true;
        let mensajesError = [];
        
        // Verificar que se haya seleccionado una mesa
        const mesaSelect = document.getElementById('mesa');
        if (!mesaSelect.value) {
            mensajesError.push('Debe seleccionar una mesa');
            formularioValido = false;
        }
        
        // Verificar que al menos un plato haya sido seleccionado
        const platosSeleccionados = document.querySelectorAll('select[name^="platos_"], select[name^="bebidas"], select[name^="postres"], select[name^="extras"]');
        let tienePlatos = false;
        
        platosSeleccionados.forEach(select => {
            if (select.value) {
                tienePlatos = true;
            }
        });
        
        if (!tienePlatos) {
            mensajesError.push('Debe agregar al menos un plato al pedido');
            formularioValido = false;
        }
        
        // Mostrar errores si existen
        if (!formularioValido) {
            // Remover mensajes de error anteriores
            const erroresAnteriores = document.querySelectorAll('.error-validacion');
            erroresAnteriores.forEach(error => error.remove());
            
            // Crear contenedor de errores
            const errorContainer = document.createElement('div');
            errorContainer.className = 'mensaje-error error-validacion';
            errorContainer.innerHTML = '<strong>Errores en el formulario:</strong><ul>' + 
                mensajesError.map(error => `<li>${error}</li>`).join('') + '</ul>';
            
            // Insertar después del banner admin
            const bannerAdmin = document.querySelector('.banner-admin');
            if (bannerAdmin) {
                bannerAdmin.parentNode.insertBefore(errorContainer, bannerAdmin.nextSibling);
            }
            
            // Hacer scroll a los errores
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return formularioValido;
    }

    // Función para limpiar advertencias de duplicados
    function limpiarAdvertencias() {
        const advertencias = document.querySelectorAll('.duplicado-warning');
        advertencias.forEach(advertencia => advertencia.remove());
    }

    // Inicializar eventos cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el total
        actualizarTotal();
        
        // Agregar evento de submit al formulario
        const formPedido = document.getElementById('form-pedido');
        if (formPedido) {
            formPedido.addEventListener('submit', function(e) {
                if (!validarFormulario()) {
                    e.preventDefault();
                } else {
                    // Limpiar advertencias antes de enviar
                    limpiarAdvertencias();
                }
            });
        }
        
        // Agregar evento change al select de mesa para limpiar errores
        const mesaSelect = document.getElementById('mesa');
        if (mesaSelect) {
            mesaSelect.addEventListener('change', function() {
                const errorValidacion = document.querySelector('.error-validacion');
                if (errorValidacion) {
                    errorValidacion.remove();
                }
            });
        }
    });

    // Función auxiliar para debug
    function debugPedido() {
        console.log('=== DEBUG PEDIDO ===');
        
        const categorias = [
            {name: 'platos_principales', container: 'platos-principales-container'},
            {name: 'bebidas', container: 'bebidas-container'},
            {name: 'postres', container: 'postres-container'},
            {name: 'extras', container: 'extras-container'}
        ];
        
        categorias.forEach(categoria => {
            console.log(`Categoría: ${categoria.name}`);
            const selects = document.querySelectorAll(`#${categoria.container} select[name="${categoria.name}[]"]`);
            const inputs = document.querySelectorAll(`#${categoria.container} input[name="cantidades_${categoria.name.split('_')[1] || categoria.name}[]"]`);
            
            selects.forEach((select, index) => {
                console.log(`  Plato: ${select.value}, Cantidad: ${inputs[index] ? inputs[index].value : 'N/A'}`);
            });
        });
        
        console.log('====================');
    }
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>