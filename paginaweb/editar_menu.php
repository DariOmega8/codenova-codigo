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
        
      
        $usuario_id = $_SESSION['id_usuario'];
        
        $query_admin = "SELECT `id administrador`, `usuario_id usuario` 
                       FROM administrador 
                       WHERE `usuario_id usuario` = $usuario_id 
                       LIMIT 1";
        
        $result_admin = mysqli_query($conexion, $query_admin);
        
        if ($result_admin && mysqli_num_rows($result_admin) > 0) {
            $admin = mysqli_fetch_assoc($result_admin);
            $admin_id = $admin['id administrador'];
            $admin_usuario_id = $admin['usuario_id usuario'];
            
            $sql = "INSERT INTO menu (tipo, estado, `administrador_id administrador`, `administrador_usuario_id usuario`) 
                    VALUES ('$tipo', '$estado', $admin_id, $admin_usuario_id)";
            
            if(mysqli_query($conexion, $sql)) {
                header("Location: administracion.php?mensaje=Menú agregado correctamente");
                exit();
            } else {
                header("Location: administracion.php?error=Error al agregar menú");
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
        
        $sql = "UPDATE menu SET tipo = '$tipo', estado = '$estado' WHERE `id menu` = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Menú actualizado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al actualizar menú");
            exit();
        }
    }

   
    if ($accion == "eliminar_menu") {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM menu WHERE `id menu` = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Menú eliminado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al eliminar menú");
            exit();
        }
    }
}
?>