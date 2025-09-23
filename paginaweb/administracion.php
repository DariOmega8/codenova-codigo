<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || $_SESSION['es_administrador'] !== true) {
    header("Location: inicio.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zona administrativa</title>
  <link rel="stylesheet" href="estilos/estilo_general.css">
  <style>
    details { margin-bottom: 15px; background: #fff; padding: 10px; border-radius: 6px; }
    summary { font-weight: bold; cursor: pointer; margin-bottom: 8px; }
    form { margin: 10px 0; }
    input, select { margin: 5px 0; padding: 6px; }
    button { padding: 6px 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background: #0056b3; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background: #f4f4f4; }
  </style>
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
         <li><a href="menu.php">Menu</a></li>
           <?php 
            if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador'] === true) {
                echo '<li><a href="administracion.php">Panel Admin</a></li>';
            }
            ?>
      </ul>
    </nav>
  </header>

  <section class="contenido">
    <h1 style="color:#fff;">Panel de administración</h1>

    
    <details>
      <summary>Creación de usuarios</summary>
      <form method="post" action="crear_usuarios.php">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br>
        <label>Fecha de nacimiento:</label><br>
        <input type="date" name="fecha" required><br>
        <label>Gmail:</label><br>
        <input type="email" name="gmail" required><br>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br>
        <label>Tipo:</label><br>
        <select name="tipo" required>
          <option value="administrador">Administrador</option>
          <option value="empleado">Empleado</option>
        </select><br>
        <button type="submit">Crear usuario</button>
      </form>
    </details>

    
    <details>
      <summary>Gestión de platos</summary>
      <form method="post" action="editar_platos.php">
        <input type="hidden" name="accion" value="agregar">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br>
        <label>Descripción:</label><br>
        <input type="text" name="descripcion" required><br>
        <label>Precio:</label><br>
        <input type="number" name="precio" required><br>
        <label>Menú:</label><br>
        <select name="menu_id">
          <?php
          $menus = mysqli_query($conexion, "SELECT * FROM `menu`");
          while($m = mysqli_fetch_assoc($menus)){
            echo "<option value='".$m['id menu']."'>".$m['tipo']."</option>";
          }
          ?>
        </select><br>
        <button type="submit">Agregar plato</button>
      </form>
    </details>

    
    <details>
      <summary>Gestión de menús</summary>
      <form method="post" action="editar_menu.php">
        <input type="hidden" name="accion" value="agregar">
        <label>Tipo:</label><br>
        <input type="text" name="tipo" required><br>
        <label>Estado:</label><br>
        <input type="text" name="estado" required><br>
        <button type="submit">Agregar menú</button>
      </form>
    </details>

   
    <details>
      <summary>Lista de reservas</summary>
      <table>
        <tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Cambiar</th></tr>
        <?php
        $reservas = mysqli_query($conexion, "SELECT * FROM `reserva`");
        while($r = mysqli_fetch_assoc($reservas)){
          echo "<tr>
                  <td>".$r['id reserva']."</td>
                  <td>".$r['fecha']."</td>
                  <td>".$r['hora de inicio']."</td>
                  <td>".$r['estado']."</td>
                  <td>
                    <form method='post' action='editar_reservas.php'>
                      <input type='hidden' name='id' value='".$r['id reserva']."'>
                      <select name='estado'>
                        <option value='Pendiente'>Pendiente</option>
                        <option value='Finalizada'>Finalizada</option>
                        <option value='Cancelada'>Cancelada</option>
                      </select>
                      <button type='submit'>Actualizar</button>
                    </form>
                  </td>
                </tr>";
        }
        ?>
      </table>
    </details>
  </section>
</main>
</body>
</html>
