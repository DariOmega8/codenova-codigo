<?php

include 'conexion.php';
session_start();

if (isset($_POST['gmail']) || isset($_POST['contraseña'])) {
    $gmail = $_POST['gmail'];
    $contrasena = $_POST['contraseña'];

       $sql = "SELECT u.`id usuario`, u.nombre, u.contraseña, 
                   a.`id administrador` as admin_id,
                   e.`id empleado` as empleado_id,
                   c.`id cliente` as cliente_id
            FROM usuario u 
            LEFT JOIN administrador a ON u.`id usuario` = a.`usuario_id usuario`
            LEFT JOIN empleado e ON u.`id usuario` = e.`usuario_id usuario`
            LEFT JOIN cliente c ON u.`id usuario` = c.`usuario_id usuario`
            WHERE u.gmail = '$gmail'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);

    if ($contrasena === $row['contraseña']) { 
            $_SESSION['id_usuario'] = $row['id usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            
            
            $_SESSION['es_administrador'] = !is_null($row['admin_id']);
            $_SESSION['es_empleado'] = !is_null($row['empleado_id']);
            $_SESSION['es_cliente'] = !is_null($row['cliente_id']);
            
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