<?php
session_start();
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $gmail = $_POST['gmail'];
    $password = $_POST['password'];
    $tipo = $_POST['tipo'];

   
    $sqlUsuario = "INSERT INTO usuario (`nombre`, `fecha de nacimiento`, `gmail`, `contraseña`) 
                   VALUES ('$nombre', '$fecha', '$gmail', '$password')";
    
    if (mysqli_query($conexion, $sqlUsuario)) {
        $id_usuario = mysqli_insert_id($conexion);

        if ($tipo == "administrador") {
            
            $sql = "INSERT INTO administrador (`ultima conexion`, `usuario_id usuario`) 
                    VALUES (NOW(), $id_usuario)";
            mysqli_query($conexion, $sql);
        } elseif ($tipo == "empleado") {
            $sql = "INSERT INTO empleado (`estado`, `fecha de ingreso`, `usuario_id usuario`) 
                    VALUES ('activo', NOW(), $id_usuario)";
            mysqli_query($conexion, $sql);
        }

        echo "Usuario creado correctamente.";
        header("Location: administracion.php"); 
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>