<?php
session_start();
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    $accion = $_POST['accion'];

    if ($accion == "agregar") {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $menu_id = $_POST['menu_id'];

        $sql = "INSERT INTO platos (nombre, descripcion, precio, `menu_id menu`)
                VALUES ('$nombre', '$descripcion', '$precio', $menu_id)";
        mysqli_query($conexion, $sql);
        echo "Plato agregado.";
    }

    if ($accion == "editar") {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        $sql = "UPDATE platos 
                SET nombre='$nombre', descripcion='$descripcion', precio='$precio'
                WHERE `id platos`=$id";
        mysqli_query($conexion, $sql);
        echo "Plato actualizado.";
    }

    if ($accion == "borrar") {
        $id = $_POST['id'];
        $sql = "DELETE FROM platos WHERE `id platos`=$id";
        mysqli_query($conexion, $sql);
        echo "Plato eliminado.";
    }
}
?>
