<?php
session_start();
include "conexion.php";

$id_administrador = $_SESSION['id_administrador'];
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion == "agregar") {
        $tipo = $_POST['tipo'];
        $estado = $_POST['estado'];
        
        $sql = "INSERT INTO menu (tipo, estado, `administrador_id administrador`, `administrador_usuario_id usuario`) 
                VALUES ('$tipo', '$estado', $id_administrador, $id_usuario)";
        
        if(mysqli_query($conexion, $sql)) {
            echo "Menú agregado.";
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }

    if ($accion == "editar") {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        $estado = $_POST['estado'];
        $sql = "UPDATE menu SET tipo='$tipo', estado='$estado' WHERE `id menu`=$id";
        if(mysqli_query($conexion, $sql)) {
            echo "Menú actualizado.";
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }

    if ($accion == "borrar") {
        $id = $_POST['id'];
        $sql = "DELETE FROM menu WHERE `id menu`=$id";
        if(mysqli_query($conexion, $sql)) {
            echo "Menú eliminado.";
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }

    header("Location: administracion.php");
    exit();
}
?>