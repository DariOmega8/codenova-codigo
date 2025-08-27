<?php

include 'conexion.php';
session_start();

if (isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $fecha_nacimiento = $_POST['fecha de nacimiento'];
    $correo = $_POST['correo'];
    $nacionalidad = $_POST['nacionalidad'];
    $id_usuario = $_SESSION['id_usuario'];
    

    $sql = "INSERT INTO clientes (nombre, fecha de nacimiento, nacionalidad, usuario_id usuario) VALUES ('$nombre', '$fecha_nacimiento', '$nacionalidad', '$id_usuario')";

    $resultado = mysqli_query($conexion, $sql);
    $id_cliente = mysqli_insert_id($conexion);

    $_SESSION['id_cliente'] = $id_cliente;

    if ($resultado) {
            header("Location: inicio.html");
            exit;
        } else {
            echo "Error en el registro: " . mysqli_error($conexion);
        }

}


?>