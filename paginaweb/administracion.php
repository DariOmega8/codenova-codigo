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
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <link rel="stylesheet" href="estilos/estilos_administracion.css">
  <link rel="stylesheet" href="estilos/reponsive.css">
</head>
<body>
<main class="principal">
  <header class="menu">
    <nav>
      <ul>
        <li><a href="inicio.php">Inicio</a></li>
        <li><a href="redes_pagos.php">Redes y pagos</a></li>
        <li><a href="reservas1.php">Reservas</a></li>
        <li><a href="zona_staff.php">Mozos orden</a></li>
        <li><a href="historia.php">Historia</a></li>
        <li><a href="cerrar_sesion.php">Cerrar Sesión (<?php echo $_SESSION['nombre']; ?>)</a></li>
      </ul>
    </nav>
  </header>

  <section class="contenido">
    <h1 style="color:#fff; text-align: center; margin-bottom: 30px;">Panel de Administración</h1>

  
    <?php if ($mensaje): ?>
        <div class="mensaje"> <?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"> <?php echo $error; ?></div>
    <?php endif; ?>

   
    <details>
      <summary> Gestión de Usuario</summary>
      <div class="form-container">
        
        <div class="form-section">
          <h3> Crear Nuevo Usuario</h3>
          <form action="crear_usuarios.php" method="post">
            <div class="form-row">
              <div class="form-group">
                <label>Nombre completo:</label>
                <input type="text" name="nombre" required placeholder="Ej: Juan Pérez">
              </div>
              <div class="form-group">
                <label>Fecha de nacimiento:</label>
                <input type="date" name="fecha" required>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Correo electrónico:</label>
                <input type="email" name="gmail" required placeholder="Ej: usuario@ejemplo.com">
              </div>
              <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required placeholder="Mínimo 6 caracteres">
              </div>
            </div>
            
            <div class="form-group">
              <label>Tipo de Usuario:</label>
              <select name="tipo" required>
                <option value="">Seleccionar tipo...</option>
                <option value="empleado">Empleado</option>
                <option value="administrador">Administrador</option>
              </select>
            </div>
            
            <button type="submit">Crear Usuario</button>
          </form>
        </div>

        
        <div class="form-section">
          <h3>Usuarios del Sistema</h3>
          <table>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
            <?php
            
            $admins = mysqli_query($conexion, "
                SELECT u.`id usuario`, u.nombre, u.gmail, 'Activo' as estado,
                       'administrador' as tipo
                FROM usuario u 
                JOIN administrador a ON u.`id usuario` = a.`usuario_id usuario`
            ");
            
            
            $empleados = mysqli_query($conexion, "
                SELECT u.`id usuario`, u.nombre, u.gmail, e.estado,
                       'empleado' as tipo
                FROM usuario u 
                JOIN empleado e ON u.`id usuario` = e.`usuario_id usuario`
            ");
            
            
            while($admin = mysqli_fetch_assoc($admins)){
              echo "<tr>
                      <td>".$admin['id usuario']."</td>
                      <td><strong>".$admin['nombre']."</strong></td>
                      <td>".$admin['gmail']."</td>
                      <td><span class='badge-admin'>Administrador</span></td>
                      <td><span class='badge-activo'>".$admin['estado']."</span></td>
                      <td>
                        <div class='action-buttons'>
                          <form action='crear_usuarios.php' method='post'>
                            <input type='hidden' name='accion' value='eliminar_usuario'>
                            <input type='hidden' name='id' value='".$admin['id usuario']."'>
                            <input type='hidden' name='tipo' value='administrador'>
                            <button type='submit' class='btn-eliminar'>Eliminar</button>
                          </form>
                        </div>
                      </td>
                    </tr>";
            }
            
            
            while($emp = mysqli_fetch_assoc($empleados)){
              $estado_badge = $emp['estado'] == 'activo' ? 'badge-activo' : 'badge-inactivo';
              echo "<tr>
                      <td>".$emp['id usuario']."</td>
                      <td><strong>".$emp['nombre']."</strong></td>
                      <td>".$emp['gmail']."</td>
                      <td><span class='badge-empleado'>Empleado</span></td>
                      <td><span class='".$estado_badge."'>".ucfirst($emp['estado'])."</span></td>
                      <td>
                        <div class='action-buttons'>
                          <form action='crear_usuarios.php' method='post'>
                            <input type='hidden' name='accion' value='eliminar_usuario'>
                            <input type='hidden' name='id' value='".$emp['id usuario']."'>
                            <input type='hidden' name='tipo' value='empleado'>
                            <button type='submit' class='btn-eliminar'>Eliminar</button>
                          </form>
                        </div>
                      </td>
                    </tr>";
            }
            ?>
          </table>
        </div>
      </div>
    </details>

    <details>
      <summary>Gestión de Platos</summary>
      <div class="form-container">
        
        <div class="form-section">
          <h3>Agregar Nuevo Plato</h3>
          <form action="editar_platos.php" method="post">
            <input type="hidden" name="accion" value="agregar_plato">
            
            <div class="form-row">
              <div class="form-group">
                <label>Nombre del Plato:</label>
                <input type="text" name="nombre" required placeholder="Ej: Lomo Saltado">
              </div>
              <div class="form-group">
                <label>Precio ($):</label>
                <input type="number" name="precio" step="0.01" min="0" required placeholder="Ej: 25.50">
              </div>
            </div>
            
            <div class="form-group">
              <label>Descripción del plato:</label>
              <input type="text" name="descripcion" required placeholder="Ej: Plato tradicional peruano">
            </div>
            
            <div class="form-group">
              <label>Menú al que pertenece:</label>
              <select name="menu_id" required>
                <option value="">Seleccionar menú...</option>
                <?php
                $menus = mysqli_query($conexion, "SELECT * FROM `menu` WHERE estado = 'activo'");
                while($m = mysqli_fetch_assoc($menus)){
                  echo "<option value='".$m['id menu']."'>".$m['tipo']."</option>";
                }
                ?>
              </select>
            </div>
            
            <button type="submit">Agregar Plato</button>
          </form>
        </div>

        <div class="form-section">
          <h3>Platos Existentes</h3>
          <table>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Precio</th>
              <th>Menú</th>
              <th>Acciones</th>
            </tr>
            <?php
            $platos = mysqli_query($conexion, "SELECT p.*, m.tipo as menu_tipo FROM platos p JOIN menu m ON p.`menu_id menu` = m.`id menu`");
            while($p = mysqli_fetch_assoc($platos)){
              echo "<tr>
                      <td>".$p['id platos']."</td>
                      <td><strong>".$p['nombre']."</strong></td>
                      <td>".$p['descripcion']."</td>
                      <td class='price-tag'>$".number_format($p['precio'], 2)."</td>
                      <td>".$p['menu_tipo']."</td>
                       <td>
                        <div class='action-buttons'>
                           <!-- Formulario para editar -->
                          <form action='editar_platos.php' method='post' class='compact-form'>
                           <input type='hidden' name='accion' value='editar_plato'>
                           <input type='hidden' name='id' value='".$p['id platos']."'>
                           <input type='text' name='nombre' value='".$p['nombre']."' size='8' required>
                           <input type='number' name='precio' value='".$p['precio']."' step='0.01' size='5' required>
                          <button type='submit' class='btn-editar'>Editar</button>
                          </form>
                            <!-- Formulario para eliminar -->
                         <form action='editar_platos.php' method='post'>
                         <input type='hidden' name='accion' value='eliminar_plato'>
                         <input type='hidden' name='id' value='".$p['id platos']."'>
                         <button type='submit' class='btn-eliminar'>Eliminar</button>
                          </form>
                        </div>
                       </td>
                    </tr>";
            }
            ?>
          </table>
        </div>
      </div>
    </details>

    <details>
      <summary>Gestión de Menús</summary>
      <div class="form-container">
        
        <div class="form-section">
          <h3>Agregar Nuevo Menú</h3>
          <form action="editar_menu.php" method="post">
            <input type="hidden" name="accion" value="agregar_menu">
            
            <div class="form-row">
              <div class="form-group">
                <label>Tipo de Menú:</label>
                <input type="text" name="tipo" required placeholder="Ej: Menú Ejecutivo">
              </div>
              <div class="form-group">
                <label>Estado del Menú:</label>
                <select name="estado" required>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
              </div>
            </div>
            
            <button type="submit">Agregar Menú</button>
          </form>
        </div>

        <div class="form-section">
          <h3>Menús Existentes</h3>
          <table>
            <tr>
              <th>ID</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
            <?php
            $menus_lista = mysqli_query($conexion, "SELECT * FROM menu");
            while($m = mysqli_fetch_assoc($menus_lista)){
              $estado_badge = $m['estado'] == 'activo' ? 'badge-activo' : 'badge-inactivo';
              echo "<tr>
                      <td>".$m['id menu']."</td>
                      <td><strong>".$m['tipo']."</strong></td>
                      <td><span class='".$estado_badge."'>".ucfirst($m['estado'])."</span></td>
                      <td>
                        <div class='action-buttons'>
                         
                          <form action='editar_menu.php' method='post' class='compact-form'>
                            <input type='hidden' name='accion' value='editar_menu'>
                            <input type='hidden' name='id' value='".$m['id menu']."'>
                            <input type='text' name='tipo' value='".$m['tipo']."' required>
                            <select name='estado'>
                              <option value='activo' ".($m['estado']=='activo'?'selected':'').">Activo</option>
                              <option value='inactivo' ".($m['estado']=='inactivo'?'selected':'').">Inactivo</option>
                            </select>
                            <button type='submit' class='btn-editar'>Editar</button>
                          </form>
                          
                         
                          <form action='editar_menu.php' method='post'>
                            <input type='hidden' name='accion' value='eliminar_menu'>
                            <input type='hidden' name='id' value='".$m['id menu']."'>
                            <button type='submit' class='btn-eliminar'>Eliminar</button>
                          </form>
                        </div>
                      </td>
                    </tr>";
            }
            ?>
          </table>
        </div>
      </div>
    </details>

    <details>
      <summary> Gestión de Reservas</summary>
      <div class="form-container">
        <div class="form-section">
          <h3>Reservas Existentes</h3>
          <table>
            <tr>
              <th>ID</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Cantidad</th>
              <th>Estado Actual</th>
              <th>Cambiar Estado</th>
            </tr>
            <?php
            $reservas = mysqli_query($conexion, "SELECT * FROM `reserva` ORDER BY fecha DESC, `hora de inicio` DESC");
            while($r = mysqli_fetch_assoc($reservas)){
              $estado_color = '';
              switch($r['estado']) {
                case 'Pendiente': $estado_color = 'background: #f39c12; color: white;'; break;
                case 'Confirmada': $estado_color = 'background: #27ae60; color: white;'; break;
                case 'Cancelada': $estado_color = 'background: #e74c3c; color: white;'; break;
                case 'Finalizada': $estado_color = 'background: #3498db; color: white;'; break;
              }
              
              echo "<tr>
                      <td>".$r['id reserva']."</td>
                      <td><strong>".$r['fecha']."</strong></td>
                      <td>".$r['hora de inicio']."</td>
                      <td>".$r['cantidad']." personas</td>
                      <td><span style='padding: 5px 10px; border-radius: 15px; font-size: 12px; $estado_color'>".$r['estado']."</span></td>
                      <td>
                        <form action='editar_reservas.php' method='post' class='compact-form'>
                          <input type='hidden' name='id' value='".$r['id reserva']."'>
                          <select name='estado_reserva' style='padding: 6px; font-size: 13px;'>
                            <option value='Pendiente' ".($r['estado']=='Pendiente'?'selected':'').">Pendiente</option>
                            <option value='Confirmada' ".($r['estado']=='Confirmada'?'selected':'').">Confirmada</option>
                            <option value='Cancelada' ".($r['estado']=='Cancelada'?'selected':'').">Cancelada</option>
                            <option value='Finalizada' ".($r['estado']=='Finalizada'?'selected':'').">Finalizada</option>
                          </select>
                          <button type='submit' style='padding: 6px 12px; font-size: 13px;'>Editar</button>
                        </form>
                      </td>
                    </tr>";
            }
            ?>
          </table>
        </div>
      </div>
    </details>
 
    <details>
      <summary>Gestión de Stock</summary>
      <div class="form-container">
        <p>Gestión completa del inventario del restaurante</p>
        <div class="action-links">
            <a href="gestion_stock.php" class="action-link stock">Ir a Gestión de Stock</a>
        </div>
      </div>
    </details>

   
    <details>
      <summary>Verificación de Reservas</summary>
      <div class="form-container">
        <p>Verificar reservas de clientes y asignar mesas</p>
        <div class="action-links">
            <a href="verificar_reserva.php" class="action-link">Ir a Verificación de Reservas</a>
        </div>
      </div>
    </details>


    <details>
      <summary>Gestión de Promociones</summary>
      <div class="form-container">
        <p>Crear y administrar promociones para clientes</p>
        <div class="action-links">
            <a href="gestion_promociones.php" class="action-link promociones">Ir a Gestión de Promociones</a>
        </div>
      </div>
    </details>


    <details>
      <summary>Estadísticas de Ventas</summary>
      <div class="form-container">
        <p>Reportes detallados de ventas y análisis de ingresos</p>
        <div class="action-links">
            <a href="estadisticas_ventas.php" class="action-link ventas">Ir a Estadísticas de Ventas</a>
        </div>
      </div>
    </details>

    <details>
      <summary>Estadísticas de Visitas</summary>
      <div class="form-container">
        <p>Analítica de tráfico y comportamiento de usuarios</p>
        <div class="action-links">
            <a href="estadisticas_visitas.php" class="action-link visitas">Ir a Estadísticas de Visitas</a>
        </div>
      </div>
    </details>

    <details>
      <summary>Gestión de Mesas</summary>
      <div class="form-container">
        <p>Crear y administrar las mesas del restaurante</p>
        <div class="action-links">
            <a href="gestion_mesas.php" class="action-link">Ir a Gestión de Mesas</a>
        </div>
      </div>
    </details>

  </section>
</main>
</body>
</html>