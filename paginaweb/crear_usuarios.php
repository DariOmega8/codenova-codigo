<?php
session_start();
include "conexion.php";


if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['tipo'])) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $fecha = $_POST['fecha'];
        $gmail = mysqli_real_escape_string($conexion, $_POST['gmail']);
        $password = mysqli_real_escape_string($conexion, $_POST['password']);
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

            header("Location: administracion.php?mensaje=Usuario creado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al crear usuario");
            exit();
        }
    }
    
    if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar_usuario') {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        
        if ($tipo == 'administrador') {
            $sql_eliminar = "DELETE FROM administrador WHERE `usuario_id usuario` = $id";
        } else {
            $sql_eliminar = "DELETE FROM empleado WHERE `usuario_id usuario` = $id";
        }
        
        mysqli_query($conexion, $sql_eliminar);
        
        $sql = "DELETE FROM usuario WHERE `id usuario` = $id";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Usuario eliminado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al eliminar usuario");
            exit();
        }
    }
}
?>