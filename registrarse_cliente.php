<?php

include 'conexion.php';
session_start();

if (isset($_POST['submit'])) {
    if (
        empty($_POST['gmail']) || empty($_POST['contraseña']) || empty($_POST['nombre'])
    || empty($_POST['fecha_nacimiento']) || empty($_POST['nacionalidad']) || empty($_POST['telefono'])
    ) {
        echo " Complete todos los campos";
    } else {
        $gmail = $_POST['gmail'];
        $contrasena = $_POST['contraseña']; 
        $nombre = $_POST['nombre'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $nacionalidad = $_POST['nacionalidad'];
        $telefono = $_POST['telefono'];

       
        $sqlUsuario = "INSERT INTO usuario (nombre, `fecha de nacimiento`, gmail, contraseña) 
                       VALUES ('$nombre', '$fecha_nacimiento', '$gmail', '$contrasena')";
        if (mysqli_query($conexion, $sqlUsuario)) {
            $id_usuario = mysqli_insert_id($conexion);
          

            $sqlCliente = "INSERT INTO cliente (nacionalidad, `usuario_id usuario`) 
                           VALUES ('$nacionalidad', '$id_usuario')";
            if (mysqli_query($conexion, $sqlCliente)) {
                $id_cliente = mysqli_insert_id($conexion);
                $_SESSION['id_cliente'] = $id_cliente;

                
                $sqlTelefono = "INSERT INTO telefono (telefono, `cliente_id cliente`, `cliente_usuario_id usuario`) 
                                VALUES ('$telefono', '$id_cliente', '$id_usuario')";
                if (mysqli_query($conexion, $sqlTelefono)) {
                    $_SESSION['id_usuario'] = $id_usuario;
                    echo " Registro exitoso";
                    header("Location: inicio.php");
                    exit;
                } else {
                    echo " Error teléfono: " . mysqli_error($conexion);
                }
            } else {
                echo " Error cliente: " . mysqli_error($conexion);
            }
        } else {
            echo " Error usuario: " . mysqli_error($conexion);
        }
    }
}

?>