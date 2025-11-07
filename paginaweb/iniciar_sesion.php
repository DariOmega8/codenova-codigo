<?php
include 'conexion.php';
session_start();

if (isset($_POST['gmail']) || isset($_POST['contraseña'])) {
    $gmail = $_POST['gmail'];
    $contrasena = $_POST['contraseña'];

    $sql = "SELECT u.id_usuario, u.nombre, u.contraseña, u.apellido, u.nacionalidad,
                   a.admin_id,
                   e.empleado_id,
                   c.cliente_id
            FROM usuario u 
            LEFT JOIN admin a ON u.id_usuario = a.usuario_id_usuario
            LEFT JOIN empleado e ON u.id_usuario = e.usuario_id_usuario
            LEFT JOIN cliente c ON u.id_usuario = c.usuario_id_usuario
            WHERE u.gmail = '$gmail'";
    
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);

        if ($contrasena === $row['contraseña']) { 
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellido'] = $row['apellido'];
            $_SESSION['nacionalidad'] = $row['nacionalidad'];
            
            $_SESSION['es_administrador'] = !is_null($row['admin_id']);
            $_SESSION['es_empleado'] = !is_null($row['empleado_id']);
            $_SESSION['es_cliente'] = !is_null($row['cliente_id']);
            
            if (!is_null($row['admin_id'])) {
                $_SESSION['admin_id'] = $row['admin_id'];
            }
            if (!is_null($row['empleado_id'])) {
                $_SESSION['empleado_id'] = $row['empleado_id'];
            }
            if (!is_null($row['cliente_id'])) {
                $_SESSION['cliente_id'] = $row['cliente_id'];
            }
            
            if ($_SESSION['es_administrador']) {
                header("Location: administracion.php");
            } elseif ($_SESSION['es_empleado']) {
                header("Location: zona_staff.php");
            } else {
                header("Location: inicio.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
}
?>