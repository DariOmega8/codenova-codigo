<?php
include 'conexion.php';
session_start();

if (isset($_POST['submit'])) {
    if (
        empty($_POST['gmail']) || empty($_POST['contraseña']) || empty($_POST['nombre']) 
        || empty($_POST['apellido']) || empty($_POST['fecha_nacimiento']) 
        || empty($_POST['nacionalidad']) || empty($_POST['telefono'])
    ) {
        echo "Complete todos los campos";
    } else {
        $gmail = $_POST['gmail'];
        $contrasena = $_POST['contraseña']; 
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $nacionalidad = $_POST['nacionalidad'];
        $telefono = $_POST['telefono'];
        $preferencias = isset($_POST['preferencias']) ? $_POST['preferencias'] : '';

        $sqlUsuario = "INSERT INTO usuario (nombre, apellido, fecha_nac, gmail, contraseña, fecha_registro, nacionalidad) 
                       VALUES ('$nombre', '$apellido', '$fecha_nacimiento', '$gmail', '$contrasena', NOW(), '$nacionalidad')";
        
        if (mysqli_query($conexion, $sqlUsuario)) {
            $id_usuario = mysqli_insert_id($conexion);
          
            $sqlCliente = "INSERT INTO cliente (preferencias, usuario_id_usuario) 
                           VALUES ('$preferencias', '$id_usuario')";
            
            if (mysqli_query($conexion, $sqlCliente)) {
                $id_cliente = mysqli_insert_id($conexion);
                $_SESSION['id_cliente'] = $id_cliente;

                $sqlTelefono = "INSERT INTO telefono (telefono, cliente_cliente_id) 
                                VALUES ('$telefono', '$id_cliente')";
                
                if (mysqli_query($conexion, $sqlTelefono)) {
                    $_SESSION['id_usuario'] = $id_usuario;
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['apellido'] = $apellido;
                    $_SESSION['nacionalidad'] = $nacionalidad;
                    $_SESSION['es_cliente'] = true;
                    
                    echo "Registro exitoso";
                    header("Location: inicio.php");
                    exit;
                } else {
                    echo "Error al registrar teléfono: " . mysqli_error($conexion);
                }
            } else {
                echo "Error al registrar cliente: " . mysqli_error($conexion);
            }
        } else {
            echo "Error al registrar usuario: " . mysqli_error($conexion);
        }
    }
}
?>