<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zona administrativa</title>
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
          <li><a href="redes_pagos.php">Redes y pagos</a></li>
          <li><a href="reservas1.php">Reservas</a></li>
          <?php if (isset($_SESSION['es_empleado']) && $_SESSION['es_empleado'] === true): ?>
            <li><a href="zona_staff.php">Mozos orden</a></li>
          <?php endif; ?>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION['nombre']); ?>)</a></li>
        </ul>
      </nav>
    </header>

    <!-- Contenido principal con sidebar -->
    <div class="contenido-con-sidebar">
      <!-- Sidebar de administración -->
      <aside class="sidebar">
        <ul>
          <li><a href="#gestion-usuarios">
            <i class="fas fa-users"></i>
            <span>Gestión de Usuarios</span>
          </a></li>
          <li><a href="#gestion-platos">
            <i class="fas fa-utensils"></i>
            <span>Gestión de Platos</span>
          </a></li>
          <li><a href="#gestion-menus">
            <i class="fas fa-book"></i>
            <span>Gestión de Menús</span>
          </a></li>
          <li><a href="#gestion-reservas">
            <i class="fas fa-calendar-check"></i>
            <span>Gestión de Reservas</span>
          </a></li>
          <li><a href="#gestion-stock">
            <i class="fas fa-boxes"></i>
            <span>Gestión de Stock</span>
          </a></li>
          <li><a href="#gestion-promociones">
            <i class="fas fa-percentage"></i>
            <span>Gestión de Promociones</span>
          </a></li>
          <li><a href="#estadisticas-ventas">
            <i class="fas fa-chart-line"></i>
            <span>Estadísticas de Ventas</span>
          </a></li>
          <li><a href="#estadisticas-visitas">
            <i class="fas fa-chart-bar"></i>
            <span>Estadísticas de Visitas</span>
          </a></li>
          <li><a href="#gestion-mesas">
            <i class="fas fa-chair"></i>
            <span>Gestión de Mesas</span>
          </a></li>
        </ul>
      </aside>

      <!-- Contenido principal -->
      <main class="contenido-principal">
        <section class="banner-admin">
          <h1>Panel de Administración</h1>
        </section>

        <!-- Mensajes -->
        <?php if ($mensaje): ?>
          <div class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
          <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Gestión de Usuarios -->
        <section id="gestion-usuarios" class="seccion-admin">
          <h2>Gestión de Usuarios</h2>
          
          <div class="formulario-container">
            <!-- Crear Nuevo Usuario -->
            <div class="formulario-seccion">
              <h3>Crear Nuevo Usuario</h3>
              <form action="crear_usuarios.php" method="post" class="formulario-admin">
                <div class="fila-formulario">
                  <div class="grupo-formulario">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" required placeholder="Ej: Juan">
                  </div>
                  <div class="grupo-formulario">
                    <label>Apellido:</label>
                    <input type="text" name="apellido" required placeholder="Ej: Pérez">
                  </div>
                </div>

                <div class="fila-formulario">
                  <div class="grupo-formulario">
                    <label>Fecha de nacimiento:</label>
                    <input type="date" name="fecha_nac" required>
                  </div>
                  <div class="grupo-formulario">
                    <label>Nacionalidad:</label>
                    <input type="text" name="nacionalidad" required placeholder="Ej: Peruana">
                  </div>
                </div>
                
                <div class="fila-formulario">
                  <div class="grupo-formulario">
                    <label>Correo electrónico:</label>
                    <input type="email" name="gmail" required placeholder="Ej: usuario@ejemplo.com">
                  </div>
                  <div class="grupo-formulario">
                    <label>Contraseña:</label>
                    <input type="password" name="password" required placeholder="Mínimo 6 caracteres">
                  </div>
                </div>
                
                <div class="grupo-formulario">
                  <label>Tipo de Usuario:</label>
                  <select name="tipo" required id="tipo-usuario">
                    <option value="">Seleccionar tipo...</option>
                    <option value="empleado">Empleado</option>
                    <option value="administrador">Administrador</option>
                  </select>
                </div>

                <!-- Campo adicional para empleados -->
                <div id="campo-salario" class="grupo-formulario" style="display: none;">
                  <label>Salario ($):</label>
                  <input type="number" name="salario" step="0.01" min="0" placeholder="Ej: 1500.00" required>
                </div>
                
                <button type="submit" class="btn-admin">Crear Usuario</button>
              </form>
            </div>

            <!-- Lista de Usuarios -->
            <div class="formulario-seccion">
              <h3>Usuarios del Sistema</h3>
              <div class="tabla-container">
                <table class="tabla-admin">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Email</th>
                      <th>Tipo</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Consulta para administradores
                    $admins = mysqli_query($conexion, "
                        SELECT u.id_usuario, u.nombre, u.apellido, u.gmail, 'Activo' as estado,
                               'administrador' as tipo
                        FROM usuario u 
                        JOIN admin a ON u.id_usuario = a.usuario_id_usuario
                    ");
                    
                    // Consulta para empleados
                    $empleados = mysqli_query($conexion, "
                        SELECT u.id_usuario, u.nombre, u.apellido, u.gmail, e.estado,
                               'empleado' as tipo
                        FROM usuario u 
                        JOIN empleado e ON u.id_usuario = e.usuario_id_usuario
                    ");
                    
                    while($admin = mysqli_fetch_assoc($admins)){
                      echo "<tr>
                              <td>".$admin['id_usuario']."</td>
                              <td><strong>".htmlspecialchars($admin['nombre'])."</strong></td>
                              <td>".htmlspecialchars($admin['apellido'])."</td>
                              <td>".htmlspecialchars($admin['gmail'])."</td>
                              <td><span class='etiqueta etiqueta-admin'>Administrador</span></td>
                              <td><span class='etiqueta etiqueta-activo'>".$admin['estado']."</span></td>
                              <td>
                                <form action='crear_usuarios.php' method='post' class='form-acciones'>
                                  <input type='hidden' name='accion' value='eliminar_usuario'>
                                  <input type='hidden' name='id' value='".$admin['id_usuario']."'>
                                  <input type='hidden' name='tipo' value='administrador'>
                                  <button type='submit' class='btn-eliminar'>Eliminar</button>
                                </form>
                              </td>
                            </tr>";
                    }
                    
                    while($emp = mysqli_fetch_assoc($empleados)){
                      $estado_clase = $emp['estado'] == 'activo' ? 'etiqueta-activo' : 'etiqueta-inactivo';
                      echo "<tr>
                              <td>".$emp['id_usuario']."</td>
                              <td><strong>".htmlspecialchars($emp['nombre'])."</strong></td>
                              <td>".htmlspecialchars($emp['apellido'])."</td>
                              <td>".htmlspecialchars($emp['gmail'])."</td>
                              <td><span class='etiqueta etiqueta-empleado'>Empleado</span></td>
                              <td><span class='etiqueta ".$estado_clase."'>".ucfirst($emp['estado'])."</span></td>
                              <td>
                                <form action='crear_usuarios.php' method='post' class='form-acciones'>
                                  <input type='hidden' name='accion' value='eliminar_usuario'>
                                  <input type='hidden' name='id' value='".$emp['id_usuario']."'>
                                  <input type='hidden' name='tipo' value='empleado'>
                                  <button type='submit' class='btn-eliminar'>Eliminar</button>
                                </form>
                              </td>
                            </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Gestión de Platos -->
        <section id="gestion-platos" class="seccion-admin">
          <h2>Gestión de Platos</h2>
          
          <div class="formulario-container">
            <!-- Agregar Nuevo Plato -->
            <div class="formulario-seccion">
              <h3>Agregar Nuevo Plato</h3>
              <form action="editar_platos.php" method="post" enctype="multipart/form-data" class="formulario-admin">
                <input type="hidden" name="accion" value="agregar_plato">
                
                <div class="fila-formulario">
                  <div class="grupo-formulario">
                    <label>Nombre del Plato:</label>
                    <input type="text" name="nombre" required placeholder="Ej: Lomo Saltado">
                  </div>
                  <div class="grupo-formulario">
                    <label>Precio ($):</label>
                    <input type="number" name="precio" step="0.01" min="0" required placeholder="Ej: 25.50">
                  </div>
                </div>
                
                <div class="grupo-formulario">
                  <label>Descripción del plato:</label>
                  <textarea name="descripcion" required placeholder="Ej: Plato tradicional peruano" rows="3"></textarea>
                </div>

                <div class="fila-formulario">
                  <div class="grupo-formulario">
                    <label>Menú al que pertenece:</label>
                    <select name="menu_id_menu" required>
                      <option value="">Seleccionar menú...</option>
                      <?php
                      // Consulta para menús disponibles
                      $menus = mysqli_query($conexion, "SELECT * FROM menu WHERE estado = 'disponible'");
                      while($m = mysqli_fetch_assoc($menus)){
                        echo "<option value='".$m['id_menu']."'>".$m['tipo']."</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="grupo-formulario">
                    <label>Imagen del plato:</label>
                    <input type="file" name="imagen" accept="image/*" required>
                  </div>
                </div>
                
                <button type="submit" class="btn-admin">Agregar Plato</button>
              </form>
            </div>

            <!-- Lista de Platos -->
            <div class="formulario-seccion">
              <h3>Platos Existentes</h3>
              <div class="tabla-container">
                <table class="tabla-admin">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Precio</th>
                      <th>Menú</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Consulta CORREGIDA: usando el nombre correcto de la columna `menu_id menu`
                    $platos = mysqli_query($conexion, "SELECT p.*, m.tipo as menu_tipo FROM plato p JOIN menu m ON p.`menu_id menu` = m.id_menu");
                    while($p = mysqli_fetch_assoc($platos)){
                      echo "<tr>
                              <td>".$p['plato_id']."</td>
                              <td><strong>".htmlspecialchars($p['nombre'])."</strong></td>
                              <td>".htmlspecialchars($p['descripcion'])."</td>
                              <td class='precio'>$".number_format($p['precio'], 2)."</td>
                              <td>".htmlspecialchars($p['menu_tipo'])."</td>
                              <td>
                                <div class='acciones-tabla'>
                                  <form action='editar_platos.php' method='post' class='form-acciones'>
                                    <input type='hidden' name='accion' value='editar_plato'>
                                    <input type='hidden' name='id' value='".$p['plato_id']."'>
                                    <input type='text' name='nombre' value='".htmlspecialchars($p['nombre'])."' required>
                                    <input type='number' name='precio' value='".$p['precio']."' step='0.01' required>
                                    <button type='submit' class='btn-editar'>Editar</button>
                                  </form>
                                  <form action='editar_platos.php' method='post' class='form-acciones'>
                                    <input type='hidden' name='accion' value='eliminar_plato'>
                                    <input type='hidden' name='id' value='".$p['plato_id']."'>
                                    <button type='submit' class='btn-eliminar'>Eliminar</button>
                                  </form>
                                </div>
                              </td>
                            </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Gestión de Menús -->
        <section id="gestion-menus" class="seccion-admin">
          <h2>Gestión de Menús</h2>
          
          <div class="formulario-container">
            <!-- Agregar Nuevo Menú -->
            <div class="formulario-seccion">
              <h3>Agregar Nuevo Menú</h3>
              <form action="editar_menu.php" method="post" class="formulario-admin">
                <input type="hidden" name="accion" value="agregar_menu">
                
                <div class="fila-formulario">
                  <div class="grupo-formulario">
                    <label>Tipo de Menú:</label>
                    <input type="text" name="tipo" required placeholder="Ej: Menú Ejecutivo">
                  </div>
                  <div class="grupo-formulario">
                    <label>Estado del Menú:</label>
                    <select name="estado" required>
                      <option value="disponible">Disponible</option>
                      <option value="no_disponible">No Disponible</option>
                    </select>
                  </div>
                </div>
                
                <button type="submit" class="btn-admin">Agregar Menú</button>
              </form>
            </div>

            <!-- Lista de Menús -->
            <div class="formulario-seccion">
              <h3>Menús Existentes</h3>
              <div class="tabla-container">
                <table class="tabla-admin">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tipo</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Consulta para menús
                    $menus_lista = mysqli_query($conexion, "SELECT * FROM menu");
                    while($m = mysqli_fetch_assoc($menus_lista)){
                      $estado_clase = $m['estado'] == 'disponible' ? 'etiqueta-activo' : 'etiqueta-inactivo';
                      echo "<tr>
                              <td>".$m['id_menu']."</td>
                              <td><strong>".htmlspecialchars($m['tipo'])."</strong></td>
                              <td><span class='etiqueta ".$estado_clase."'>".ucfirst($m['estado'])."</span></td>
                              <td>
                                <div class='acciones-tabla'>
                                  <form action='editar_menu.php' method='post' class='form-acciones'>
                                    <input type='hidden' name='accion' value='editar_menu'>
                                    <input type='hidden' name='id' value='".$m['id_menu']."'>
                                    <input type='text' name='tipo' value='".htmlspecialchars($m['tipo'])."' required>
                                    <select name='estado'>
                                      <option value='disponible' ".($m['estado']=='disponible'?'selected':'').">Disponible</option>
                                      <option value='no_disponible' ".($m['estado']=='no_disponible'?'selected':'').">No Disponible</option>
                                    </select>
                                    <button type='submit' class='btn-editar'>Editar</button>
                                  </form>
                                  <form action='editar_menu.php' method='post' class='form-acciones'>
                                    <input type='hidden' name='accion' value='eliminar_menu'>
                                    <input type='hidden' name='id' value='".$m['id_menu']."'>
                                    <button type='submit' class='btn-eliminar'>Eliminar</button>
                                  </form>
                                </div>
                              </td>
                            </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Gestión de Reservas -->
        <section id="gestion-reservas" class="seccion-admin">
          <h2>Gestión de Reservas</h2>
          
          <div class="formulario-container">
            <div class="formulario-seccion">
              <h3>Reservas Existentes</h3>
              <div class="tabla-container">
                <table class="tabla-admin">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Cantidad</th>
                      <th>Estado Actual</th>
                      <th>Cambiar Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Consulta para reservas
                    $reservas = mysqli_query($conexion, "SELECT * FROM reserva ORDER BY fecha DESC, hora_inicio DESC");
                    while($r = mysqli_fetch_assoc($reservas)){
                      $estado_clase = '';
                      switch($r['estado']) {
                        case 'pendiente': $estado_clase = 'etiqueta-pendiente'; break;
                        case 'confirmada': $estado_clase = 'etiqueta-confirmada'; break;
                        case 'cancelada': $estado_clase = 'etiqueta-cancelada'; break;
                        case 'completada': $estado_clase = 'etiqueta-finalizada'; break;
                      }
                      
                      echo "<tr>
                              <td>".$r['id_reserva']."</td>
                              <td><strong>".$r['fecha']."</strong></td>
                              <td>".$r['hora_inicio']."</td>
                              <td>".$r['cantidad']." personas</td>
                              <td><span class='etiqueta ".$estado_clase."'>".ucfirst($r['estado'])."</span></td>
                              <td>
                                <form action='editar_reservas.php' method='post' class='form-acciones'>
                                  <input type='hidden' name='id' value='".$r['id_reserva']."'>
                                  <select name='estado_reserva'>
                                    <option value='pendiente' ".($r['estado']=='pendiente'?'selected':'').">Pendiente</option>
                                    <option value='confirmada' ".($r['estado']=='confirmada'?'selected':'').">Confirmada</option>
                                    <option value='cancelada' ".($r['estado']=='cancelada'?'selected':'').">Cancelada</option>
                                    <option value='completada' ".($r['estado']=='completada'?'selected':'').">Completada</option>
                                  </select>
                                  <button type='submit' class='btn-editar'>Actualizar</button>
                                </form>
                              </td>
                            </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Otras secciones -->
        <section id="gestion-stock" class="seccion-admin">
          <h2>Gestión de Stock</h2>
          <p>Gestión completa del inventario del restaurante</p>
          <a href="gestion_stock.php" class="btn-enlace">Ir a Gestión de Stock</a>
        </section>

        <section id="gestion-promociones" class="seccion-admin">
          <h2>Gestión de Promociones</h2>
          <p>Crear y administrar promociones para clientes</p>
          <a href="gestion_promociones.php" class="btn-enlace">Ir a Gestión de Promociones</a>
        </section>

        <section id="estadisticas-ventas" class="seccion-admin">
          <h2>Estadísticas de Ventas</h2>
          <p>Reportes detallados de ventas y análisis de ingresos</p>
          <a href="estadisticas_ventas.php" class="btn-enlace">Ir a Estadísticas de Ventas</a>
        </section>

        <section id="estadisticas-visitas" class="seccion-admin">
          <h2>Estadísticas de Visitas</h2>
          <p>Analítica de tráfico y comportamiento de usuarios</p>
          <a href="estadisticas_visitas.php" class="btn-enlace">Ir a Estadísticas de Visitas</a>
        </section>

        <section id="gestion-mesas" class="seccion-admin">
          <h2>Gestión de Mesas</h2>
          <p>Crear y administrar las mesas del restaurante</p>
          <a href="gestion_mesas.php" class="btn-enlace">Ir a Gestión de Mesas</a>
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

      // Mostrar/ocultar campo de salario según tipo de usuario
      const tipoSelect = document.getElementById('tipo-usuario');
      const campoSalario = document.getElementById('campo-salario');
      
      if (tipoSelect && campoSalario) {
        tipoSelect.addEventListener('change', function() {
          if (this.value === 'empleado') {
            campoSalario.style.display = 'block';
            campoSalario.querySelector('input').required = true;
          } else {
            campoSalario.style.display = 'none';
            campoSalario.querySelector('input').required = false;
          }
        });
      }
    });
  </script>
</body>
</html>