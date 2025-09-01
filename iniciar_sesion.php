<?php

include 'conexion.php';
session_start();

if (isset($_POST['gmail']) || isset($_POST['contraseña'])) {
    $gmail = $_POST['gmail'];
    $contrasena = $_POST['contraseña'];

    $sql = "SELECT `id usuario`, nombre, contraseña FROM usuario WHERE gmail = '$gmail'";
    $resultado = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($resultado)) {
        if ($contrasena === $row['contraseña']) { // comparación directa (sin password_hash)
            $_SESSION['id_usuario'] = $row['id usuario'];
            $_SESSION['nombre'] = $row['nombre'];
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