<?php

include 'conexion.php';
session_start();

if (isset($_POST['gmail']) || isset($_POST['contraseña'])) {
    $gmail = $_POST['gmail'];
    $contrasena = $_POST['contraseña'];

    $sql = "SELECT u.`id usuario`, u.nombre, u.contraseña, a.`id administrador` as admin_id
            FROM usuario u LEFT JOIN administrador a ON u.`id usuario` = a.`usuario_id usuario`
            WHERE u.gmail = '$gmail'";
    $resultado = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($resultado)) {
        if ($contrasena === $row['contraseña']) { 
            $_SESSION['id_usuario'] = $row['id usuario'];
            $_SESSION['nombre'] = $row['nombre'];

            if (!is_null($row['admin_id'])) {
                $_SESSION['es_administrador'] = true;
                $_SESSION['id_administrador'] = $row['admin_id'];
            } else {
                $_SESSION['es_administrador'] = false;
            }

            header("Location: inicio.php");
            exit;
        } else {
            echo " Contraseña incorrecta";
        }
    } else {
        echo " Usuario no encontrado";
    }
}

?>