<?php
session_start();
include "conexion.php";


if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

   
    if ($accion == "agregar_plato") {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = $_POST['precio'];
        $menu_id = $_POST['menu_id'];

        if(!is_numeric($precio)) {
            header("Location: administracion.php?error=El precio debe ser un número");
            exit();
        }

        $sql = "INSERT INTO platos (nombre, descripcion, precio, `menu_id menu`)
                VALUES ('$nombre', '$descripcion', $precio, $menu_id)";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato agregado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al agregar plato");
            exit();
        }
    }

   
    if ($accion == "editar_plato") {
        $id = $_POST['id'];
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = $_POST['precio'];
        $menu_id = $_POST['menu_id'];

        $sql = "UPDATE platos 
                SET nombre = '$nombre', 
                    descripcion = '$descripcion', 
                    precio = $precio,
                    `menu_id menu` = $menu_id
                WHERE `id platos` = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato actualizado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al actualizar plato");
            exit();
        }
    }

 
    if ($accion == "eliminar_plato") {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM platos WHERE `id platos` = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato eliminado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al eliminar plato");
            exit();
        }
    }
}
?>