<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion == "agregar_menu") {
        $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        
        // Obtener el admin_id del usuario administrador actual
        $usuario_id = $_SESSION['id_usuario'];
        
        // Consulta actualizada para admin
        $query_admin = "SELECT admin_id 
                       FROM admin 
                       WHERE usuario_id_usuario = $usuario_id 
                       LIMIT 1";
        
        $result_admin = mysqli_query($conexion, $query_admin);
        
        if ($result_admin && mysqli_num_rows($result_admin) > 0) {
            $admin = mysqli_fetch_assoc($result_admin);
            $admin_id = $admin['admin_id'];
            
            // Consulta actualizada para menu (sin clave compuesta)
            $sql = "INSERT INTO menu (tipo, estado, admin_admin_id) 
                    VALUES ('$tipo', '$estado', $admin_id)";
            
            if(mysqli_query($conexion, $sql)) {
                header("Location: administracion.php?mensaje=Menú agregado correctamente");
                exit();
            } else {
                header("Location: administracion.php?error=Error al agregar menú: " . mysqli_error($conexion));
                exit();
            }
        } else {
            header("Location: administracion.php?error=No se encontró administrador para tu usuario");
            exit();
        }
    }

    if ($accion == "editar_menu") {
        $id = $_POST['id'];
        $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        
        // Consulta actualizada
        $sql = "UPDATE menu SET tipo = '$tipo', estado = '$estado' WHERE id_menu = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Menú actualizado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al actualizar menú: " . mysqli_error($conexion));
            exit();
        }
    }

    if ($accion == "eliminar_menu") {
        $id = $_POST['id'];
        
        // Consulta actualizada
        $sql = "DELETE FROM menu WHERE id_menu = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Menú eliminado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al eliminar menú: " . mysqli_error($conexion));
            exit();
        }
    }
}
?>